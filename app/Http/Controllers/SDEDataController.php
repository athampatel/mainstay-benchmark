<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SDEApi;
use App\Models\User;

class SDEDataController extends Controller
{

    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
    }

    public function getCustomerSalesHistory(){

        $data = array(            
            "filter" => [
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => "00",
                    "operator" => "and"
                ],
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" =>  "GEMWI00",
                    "operator" =>  "and"
                ],
                [
                    "column" =>  "FiscalYear",
                    "type" =>  "equals",
                    "value" =>  "2021",
                    "operator" =>  "and"
                ]
            ]
        );

        $response   = $this->SDEApi->Request('post','CustomerSalesHistory',$data);
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
