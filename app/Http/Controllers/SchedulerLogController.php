<?php

namespace App\Http\Controllers;

use App\Models\SchedulerLog;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetails;
use App\Helpers\SDEApi;
use Illuminate\Support\Facades\Storage;
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

    public static function doScheduler(){
        $schduler = SchedulerLog::where('completed',0)->get()->toArray();

    }

    

    public static function runScheduler($user_id = 0){
        $SDEApi     = new SDEApi();
       
        $customers = User::leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                    ->where('users.active',1)->select('user_details.*')
                    ->select(['users.*','user_details.id as details_id','user_details.ardivisionno','user_details.customerno'])
                    ->get()->toArray(); 
        if(!empty($customers)){
            foreach($customers as $customer){
                $year       = 2022;
                $endyear    = 2022;               
                $limit      = 100;
                while($year <= $endyear){
                    $completed      = 0;
                    $page           = 0;
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
                    }else{
                        $_data      = json_decode($schduler['filter'],true);
                        $page       = $schduler['current_page'];
                        $completed  = $schduler['completed'];
                        $page       = $schduler['current_page'];
                        if($page <= 1){
                            $page = $limit + 1;
                        }else{
                            $page = ($page * $limit) + 1;
                            $page = $page + 1;
                        }

                        $_data['offset'] = $page; 
                    }                   
                    if(!$completed){
                        $orders   = $SDEApi->Request('post','SalesOrderHistoryHeader',$_data);
                         

                        $fileName = $customer['id'].'-'.$year.'-'.$customer['customerno'].'-SalesOrderHistoryHeader.json';
                        $total     = 0;
                        if(!empty($orders)){                        
                            
                            /*if(isset($orders['salesorderhistoryheader'])){
                                $salesorderhistoryheader = $orders['salesorderhistoryheader'];
                            }elseif(isset($orders['SalesOrderHistoryHeader'])){
                                $salesorderhistoryheader = $orders['SalesOrderHistoryHeader'];
                            }*/
                            //if(!empty($salesorderhistoryheader)){
                            Storage::disk('json')->put($fileName,json_encode($orders));
                                
                            //}
                            if(isset($orders['meta'])){
                                $total   = $orders['meta']['records'];
                            }else{
                                $total   = -1;
                            }

                            
                        }

                        $all    =    $limit;
                        if($page > 0)
                            $all = ($page * $limit) + 1;

                       //$is_completed = 0;
                        if($year > date('Y') && ($total <= $limit || $total >= $all))
                            $is_completed = 1;
                        elseif($total <= 0 || $total <= $limit)
                            $is_completed = 1;
                      
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
                        }
                        
                        echo "<pre>";
                        print_r($_data);
                        print_r($orders);    
                    }
                    $year++;
                }
            }
        }            
    }
}

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
