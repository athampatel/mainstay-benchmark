<?php

namespace App\Http\Controllers;

use App\Models\SchedulerLog;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetails;
use App\Helpers\SDEApi;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use File;

class SchedulerLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchedulerLog  $schedulerLog
     * @return \Illuminate\Http\Response
     */
    public function show(SchedulerLog $schedulerLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SchedulerLog  $schedulerLog
     * @return \Illuminate\Http\Response
     */
    public function edit(SchedulerLog $schedulerLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchedulerLog  $schedulerLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchedulerLog $schedulerLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchedulerLog  $schedulerLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchedulerLog $schedulerLog)
    {
        //
    }

    //https://www.cloudways.com/blog/laravel-cron-job-scheduling/

    public static function CreateScheduler($user_id =  0){        
        $customers = User::leftjoin('user_details','users.id','=','user_details.user_id')
                        ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                        ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                        ->where('users.active',1)->where('users.id',$user_id)
                        ->select(['users.*','user_details.id as details_id','user_details.ardivisionno','user_details.customerno'])
                        ->get()->toArray();
        $limit  = 200;              
        if(!empty($customers)){
            foreach($customers as $customer){
                $year       = 2018;
                $endyear    = date('Y'); 
                while($year <= $endyear){
                    $completed      = 0;
                    $page           = -1;
                    $resourcetype   = 'SalesOrderHistoryHeader';
                    $schduler       = SchedulerLog::where('user_details_id',$customer['details_id'])
                                            ->where('resource',$resourcetype)
                                            ->where('index_type',$year)
                                            ->get()->first();
                    if(!$schduler){
                        $_data  = array('offset' => $page,
                                        'limit' => $limit,
                                        'filter' =>array(array(   
                                                            'column'   => 'ARDivisionNo', 
                                                            'type'      => 'equals', 
                                                            'value'     => $customer['ardivisionno'],
                                                            'operator'  => 'and'),
                                                /* array(  'column'   => 'nontaxablesalesamt', 
                                                            'type'      => '>', 
                                                            'value'     => '0',
                                                            'operator'  => 'and'),  */ 
                                                    array(  'column'   => 'CustomerNo', 
                                                            'type'      => 'equals', 
                                                            'value'     => $customer['customerno'],
                                                            'operator'  => 'and'),    
                                                    array(  'column'   => 'invoiceDate', 
                                                            'type'      => '>', 
                                                            'value'     => $year,
                                                            'operator'  => 'and'),         
                                                        )
                                            );
                        $total          = 0;
                        $is_completed   = 0;                    
                        $schedulerLog = array(  'user_details_id'   => $customer['details_id'],
                                                'resource'          => $resourcetype,
                                                'filter'            => json_encode($_data),
                                                'index_type'        => $year,
                                                'total'             => $total,
                                                'current_page'      => $page,
                                                'completed'         => $is_completed);
                        SchedulerLog::create($schedulerLog);

                    }
                    $year++;
                }
            }
        }
        return true;
    }

    

    public static function runScheduler($user_id = 0){
        $SDEApi     = new SDEApi();        
        $interval   = 60;
       
        $collection = SchedulerLog::leftjoin('user_details','scheduler_logs.user_details_id','=','user_details.id')
                                ->leftjoin('users','user_details.user_id','=','users.id')                              
                                ->where('scheduler_logs.completed',0)                                
                                ->where('users.active',1)
                                ->where('users.is_deleted',0)->orderBy('scheduler_logs.updated_at','ASC')->orderBy('scheduler_logs.current_page','ASC')
                                ->select(['scheduler_logs.*','users.id as user_id','user_details.id as details_id','user_details.ardivisionno','user_details.customerno'])->get()->toArray();
        
        $limit  = 200;
        $resourcetype   = 'SalesOrderHistoryHeader';
        if(!empty($collection)){
            foreach($collection as $tasks){
                $year           = $tasks['index_type'];
                $id             = $tasks['id'];              
                $completed      = $tasks['completed'];              
                $page           = $tasks['current_page'];              
                $page           = $tasks['total']; 
                $_data          = json_decode($tasks['filter'],true); 
                $offset         = 0;
                if($page <= 0){                           
                    $page = 0;
                }else{
                    $offset = ($page * $limit) + 1;
                    $page = $page + 1;
                }

                $_data['offset'] = $offset;
                if(!$completed){
                    $orders   = $SDEApi->Request('post','SalesOrderHistoryHeader',$_data);
                    $fileName = $tasks['details_id'].'-page-'.$page.'-'.$year.'-'.$tasks['customerno'].'-SalesOrderHistoryHeader.json';
                    $total     = 0;
                    if(!empty($orders)){                        
                        Storage::disk('json')->put($fileName,json_encode($orders));
                        if(isset($orders['meta'])){
                            $total   = $orders['meta']['records'];
                        }else{
                            $total   = -1;
                        }
                    }

                    $all    =    $limit;
                    if($page > 0)
                        $all = ($page * $limit) + 1;

                    $is_completed   = 0;
                    $total_records  = $offset + $limit;

                    if($total_records >= $total && $year < date('Y'))
                        $is_completed = 1;
                    elseif($total <= 0  && $year != date('Y'))
                        $is_completed = 1;
                    elseif($year == date('Y') && $total_records >= $total)    
                        $page = 0;

                    $schedulerLog = array(  'user_details_id'   => $tasks['details_id'],
                                            'resource'          => $resourcetype,
                                            'filter'            => json_encode($_data),
                                            'index_type'        => $year,
                                            'total'             => $total,
                                            'current_page'      => $page,
                                            'completed'         => $is_completed);

                    SchedulerLog::where('id',$id)->update($schedulerLog);

                   /* if(!$schduler){
                        SchedulerLog::create($schedulerLog);
                    }else{
                        $schduler['filter']         = json_encode($_data);
                        $schduler['total']          = $total;
                        $schduler['current_page']   = $page;
                        $schduler['completed']      = $is_completed;
                        $schduler->save();
                    }*/
                    
                }
            }
        }                 
        return true;          
    }
}


/*if(!empty($customers)){
            foreach($customers as $customer){
                $year       = 2018;
                $endyear    = date('Y');               
                $limit      = 200;
                while($year <= $endyear){
                    $completed      = 0;
                    $page           = 0;
                    $resourcetype   = 'SalesOrderHistoryHeader';
                    $schduler       = SchedulerLog::where('user_details_id',$customer['details_id'])
                                            ->where('resource',$resourcetype)
                                            ->where('index_type',$year)
                                            ->get()->first();
                    $offset = 0;
                    if(!$schduler){
                       
                        $_data  = array('offset' => $page,
                                        'limit' => $limit,
                                        'filter' =>array(array(   
                                                            'column'   => 'ARDivisionNo', 
                                                            'type'      => 'equals', 
                                                            'value'     => $customer['ardivisionno'],
                                                            'operator'  => 'and'),                                                  
                                                    array(  'column'   => 'CustomerNo', 
                                                            'type'      => 'equals', 
                                                            'value'     => $customer['customerno'],
                                                            'operator'  => 'and'),    
                                                    array(  'column'   => 'invoiceDate', 
                                                            'type'      => '>', 
                                                            'value'     => $year,
                                                            'operator'  => 'and'),         
                                                        )
                        );
                    }else{
                        $_data      = json_decode($schduler['filter'],true);
                        $page       = $schduler['current_page'];
                        $completed  = $schduler['completed'];
                        $page       = $schduler['current_page'];
                        if($page <= 0){                           
                            $page = 1;
                        }else{
                            $offset = ($page * $limit) + 1;
                            $page = $page + 1;
                        }                        
                        $_data['offset'] = $offset;
                       
                    }    
                                   
                    if(!$completed){                       
                        $orders   = $SDEApi->Request('post','SalesOrderHistoryHeader',$_data);
                         

                        $fileName = $customer['details_id'].'-page-'.$page.'-'.$year.'-'.$customer['customerno'].'-SalesOrderHistoryHeader.json';
                        $total     = 0;
                        if(!empty($orders)){                        
                            Storage::disk('json')->put($fileName,json_encode($orders));
                            if(isset($orders['meta'])){
                                $total   = $orders['meta']['records'];
                            }else{
                                $total   = -1;
                            }
                        }
                        $all    =    $limit;
                        if($page > 0)
                            $all = ($page * $limit) + 1;

                        $is_completed = 0;
                        $total_records = $offset + $limit;

                        if($total_records >= $total && $year < date('Y'))
                            $is_completed = 1;                        
                        elseif($total <= 0  && $year != date('Y'))
                            $is_completed = 1;
                        elseif($year == date('Y') && $total_records >= $total)    
                            $page = 0;
                        

                        $schedulerLog = array(  'user_details_id'   => $customer['details_id'],
                                                'resource'          => $resourcetype,
                                                'filter'            => json_encode($_data),
                                                'index_type'        => $year,
                                                'total'             => $total,
                                                'current_page'      => $page,
                                                'completed'         => $is_completed);
                        if(!$schduler){
                            SchedulerLog::create($schedulerLog);
                        }else{
                            $schduler['filter']         = json_encode($_data);
                            $schduler['total']          = $total;
                            $schduler['current_page']   = $page;
                            $schduler['completed']      = $is_completed;
                            $schduler->save();
                        }
                       
                    }
                    $year++;
                }
            }
        }   */
/*
{
"user":"Mainstay",
"password":"M@1nSt@y",
"resource":"SalesOrderHistoryHeader",
"filter":[
{
"column":"ARDivisionNo",
"type":"equals",
"value":"00",
"operator":"and"
},
{
"column":"nontaxablesalesamt",
"type":">",
"value":"0",
"operator":"and"
},
{
"column":"CustomerNo",
"type":"begins",
"value":"GEMWI00",
"operator":"and"
},
{
"column":"invoiceDate",
"type":">",
"value":"2022",
"operator":"and"
}
],
"offset":1,
"limit":700
}

*/
