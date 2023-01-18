<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SDEApi;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Auth;

class SDEDataController extends Controller
{

    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
    }

    public function getCustomerSalesHistory(){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user_details = UserDetails::where('user_id',$user_id)->first();
        // $year = '2020';
        $year = date('Y');
        $data = array(            
            "filter" => [
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => $user_details->ardivisionno,
                    "operator" => "and"
                ],
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" =>  $user_details->customerno,
                    "operator" =>  "and"
                ],
                [
                    "column" =>  "FiscalYear",
                    "type" =>  "equals",
                    "value" =>  $year,
                    "operator" =>  "and"
                ]
            ]
        );

        $response_data   = $this->SDEApi->Request('post','CustomerSalesHistory',$data);
        $response = ['success' => true , 'data' => [ 'data' => $response_data, 'year' => $year]];
        echo \json_encode($response);
    }

    // invoice orders
    public function getCustomerInvoiceOrders(){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user_details = UserDetails::where('user_id',$user_id)->first();
        // $year = date('Y-01-01');
        // $year = '';
        // dd($year);
        $data = array(            
            "filter" => [
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => $user_details->ardivisionno,
                    "operator" => "and"
                ],
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" =>  $user_details->customerno,
                    "operator" =>  "and"
                ],
                // [
                //     "column" =>  "orderdate",
                //     "type" =>  "greaterthan",
                //     "value" =>  $year,
                //     "operator" =>  "and"
                // ]
            ]
        );

        $data = array(            
            "offset" => 1,
            "limit" => 5,
        );

        $response_data   = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
        foreach($response_data['salesorderhistoryheader'] as $key => $res){
            $data1 = array(            
                "filter" => [
                    [
                        "column" => "SalesOrderNo",
                        "type" => "equals",
                        "value" => $res['salesorderno'],
                        "operator" => "and"
                    ],
                    ]
            );  
            $response_data1   = $this->SDEApi->Request('post','SalesOrderHistoryDetail',$data1);
            // echo '___data';
            // print_r($response_data1);
            // die();
            // dd($response_data1);
        };

        // $response_data   = $this->SDEApi->Request('post','CustomerSalesHistory',$data);
        // $response = ['success' => true , 'data' => [ 'data' => $response_data, 'year' => $year]];
        $response = ['success' => true , 'data' => [ 'data' => $response_data, 'year' => '']];
        echo \json_encode($response);
    }


    public function getCustomerItemHistory(){
        $data = array(            
            "filter" => [
                [
                    "column"=> "ARDivisionNo",
                    "type"=> "equals",
                    "value"=> "00",
                    "operator"=> "and"
                ],
                [
                    "column"=> "CustomerNo",
                    "type"=> "equals",
                    "value"=> "GEMWI00",
                    "operator"=> "and"
                ],
                [
                    "column"=> "FiscalCalYear",
                    "type"=> "equals",
                    "value"=> "2021",
                    "operator"=> "and"
                ]
            ]
        );

        $response   = $this->SDEApi->Request('post','CustomerItemHistory',$data);
        echo \json_encode($response);
    }


    // public function getInvoiceHistoryHeader(){
    //     $data = array(            
    //         "filter" => [
    //             [
    //                 "column" => "ARDivisionNo",
    //                 "type" => "equals",
    //                 "value" => "00",
    //                 "operator" => "and"
    //             ],
    //             [
    //                 "column" => "CustomerNo",
    //                 "type" => "equals",
    //                 "value" => "GEMWI00",
    //                 "operator" => "and"
    //             ],
    //         ]
    //     );

    //     $response   = $this->SDEApi->Request('post','InvoiceHistoryHeader',$data);
    //     echo \json_encode($response);
    // }

    // Api responses checking

    public function getAliasItems(){
        $data = array(            
            // "filter" => [
            //     [
            //         "column" => "ARDivisionNo",
            //         "type" => "equals",
            //         "value" => "00",
            //         "operator" => "and"
            //     ],
            //     [
            //         "column" => "CustomerNo",
            //         "type" => "equals",
            //         "value" => "GEMWI00",
            //         "operator" => "and"
            //     ],
            // ]
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','AliasItems',$data);
        // echo \json_encode($response);
        dd($response);
    }

    public function getCustomers(){
        $data = array(            
            // "filter" => [
            //     [
            //         "column" => "ARDivisionNo",
            //         "type" => "equals",
            //         "value" => "00",
            //         "operator" => "and"
            //     ],
            //     [
            //         "column" => "CustomerNo",
            //         "type" => "equals",
            //         "value" => "GEMWI00",
            //         "operator" => "and"
            //     ],
            // ]
            "offset" => 1,
            "limit" => 20,
        );

        $response   = $this->SDEApi->Request('post','Customers',$data);
        // echo \json_encode($response);
        dd($response);
        // Customers
    }

    public function getInvoiceHistoryDetail(){
        $data = array(            
            // "filter" => [
            //     [
            //         "column" => "ARDivisionNo",
            //         "type" => "equals",
            //         "value" => "00",
            //         "operator" => "and"
            //     ],
            //     [
            //         "column" => "CustomerNo",
            //         "type" => "equals",
            //         "value" => "GEMWI00",
            //         "operator" => "and"
            //     ],
            // ]
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','InvoiceHistoryDetail',$data);
        // echo \json_encode($response);
        dd($response);
    }

    public function getInvoiceHistoryHeader(){
        $data = array(            
            // "filter" => [
            //     [
            //         "column" => "ARDivisionNo",
            //         "type" => "equals",
            //         "value" => "00",
            //         "operator" => "and"
            //     ],
            //     [
            //         "column" => "CustomerNo",
            //         "type" => "equals",
            //         "value" => "GEMWI00",
            //         "operator" => "and"
            //     ],
            // ]
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','InvoiceHistoryHeader',$data);
        // echo \json_encode($response);
        dd($response);
    }
    
    public function getItemWarehouses(){
        $data = array(            
            // "filter" => [
            //     [
            //         "column" => "ARDivisionNo",
            //         "type" => "equals",
            //         "value" => "00",
            //         "operator" => "and"
            //     ],
            //     [
            //         "column" => "CustomerNo",
            //         "type" => "equals",
            //         "value" => "GEMWI00",
            //         "operator" => "and"
            //     ],
            // ]
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','ItemWarehouses',$data);
        // echo \json_encode($response);
        dd($response);
    }
    
    public function getProducts(){
        $data = array(            
            // "filter" => [
            //     [
            //         "column" => "ARDivisionNo",
            //         "type" => "equals",
            //         "value" => "00",
            //         "operator" => "and"
            //     ],
            //     [
            //         "column" => "CustomerNo",
            //         "type" => "equals",
            //         "value" => "GEMWI00",
            //         "operator" => "and"
            //     ],
            // ]
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','Products',$data);
        // echo \json_encode($response);
        dd($response);
    }
    
    public function getSalesOrderHistoryDetail(){
        $data = array(            
            // "filter" => [
            //     [
            //         "column" => "ARDivisionNo",
            //         "type" => "equals",
            //         "value" => "00",
            //         "operator" => "and"
            //     ],
            //     [
            //         "column" => "CustomerNo",
            //         "type" => "equals",
            //         "value" => "GEMWI00",
            //         "operator" => "and" 
            //     ],
            // ]
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','SalesOrderHistoryDetail',$data);
        // echo \json_encode($response);
        dd($response);
    }
    
    public function getSalesOrderHistoryHeader(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
        // echo \json_encode($response);
        dd($response);
    }

    public function getSalespersons(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('get','Salespersons',$data);
        // echo \json_encode($response);
        dd($response);
    }
    
    public function getVendors(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','Vendors',$data);
        // echo \json_encode($response);
        dd($response);
    }

    public function changeUserStatus($id){
        $user = User::find($id)->toArray();
        return view('pages.user-active',compact('user'));
    }

    public function changeUserActive($id){  
        $user = User::find($id);
        $user->active = 1;
        $user->save();
        return redirect()->back();
    }

    public function changeUserCancel($id){
        $user = User::find($id);
        dd($user);
    }
}
