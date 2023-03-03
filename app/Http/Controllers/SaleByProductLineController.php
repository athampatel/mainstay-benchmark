<?php

namespace App\Http\Controllers;

use App\Models\SaleByProductLine;
use Illuminate\Http\Request;
use App\Helpers\SDEApi;

class SaleByProductLineController extends Controller
{

    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
    }

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
     * @param  \App\Models\SaleByProductLine  $saleByProductLine
     * @return \Illuminate\Http\Response
     */
    public function show(SaleByProductLine $saleByProductLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleByProductLine  $saleByProductLine
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleByProductLine $saleByProductLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleByProductLine  $saleByProductLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleByProductLine $saleByProductLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleByProductLine  $saleByProductLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleByProductLine $saleByProductLine)
    {
        //
    }
    public static function getSaleDetails($customer,$year = '', $month = '', $product_line = ''){
        if(empty($customer))
            return false;
        $customer_no = isset($customer['customerno']) ? $customer['customerno'] : '';
        $ardivisionno = isset($customer['ardivisionno']) ? $customer['ardivisionno'] : '';
        $customer_id = isset($customer['id']) ? $customer['id'] : '';
        
        if($customer_no == '' || $ardivisionno == '')
            return false;

        if($year == '')
            $year = date('Y');
        
        $saledetails    = array(); 
        $sale_details = SaleByProductLine::where('user_details_id',$customer_id)->where('year',$year)->get()->toArray();
        
       // dd($sale_details);

        if(empty($sale_details)){
            $_data = array('year' => $year,'ARDivisionNo' => $ardivisionno,'CustomerNo' => $customer_no);
            $SDEApi = new SDEApi();

           
            

           $prroductLine = $SDEApi->Request('post','SalesByCustProdLine',$_data); 

        //    dd($prroductLine);
           


           $responsedata    = $prroductLine;
           
           if(isset($responsedata['salesbyproductline'])){
                foreach($responsedata['salesbyproductline'] as $key => $line_data){
                    $ProductLine = $line_data['productline'];
                    for($i = 1;$i<=12;$i++){                        
                        if(isset($line_data['DollarsSoldPeriod'.$i]) && $line_data['DollarsSoldPeriod'.$i] > 0){
                            $line_item = SaleByProductLine::where('user_details_id',$customer_id)
                                                            ->where('year',$year)
                                                            ->where('month',$i)
                                                            ->where('ProductLine',$ProductLine)->first();

                            $array = array('year'               => $year,
                                           'month'              => $i,
                                           'value'               => $line_data['DollarsSoldPeriod'.$i],
                                           'user_details_id'    => $customer_id,
                                           'ProductLine'       => $ProductLine);

                            if($line_item){
                                $line_item->save($array);
                            }else{
                               SaleByProductLine::create($array);
                            }                            
                            $saledetails[$ProductLine][$year][$i] = $array;
                        }
                    }
                }
           }
        }else{
            foreach($sale_details as $line_data){
                $ProductLine    = $line_data['ProductLine'];
                $year           = $line_data['year'];
                $month          = $line_data['month'];                
                $saledetails[$ProductLine][$year][$month] = $line_data['value'];    
            }
        }   

       
        return $saledetails;
    }

}
