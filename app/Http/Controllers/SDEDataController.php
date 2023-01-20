<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SDEApi;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Auth;

ini_set('max_execution_time', 300);

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
        $user = User::find($user_id)->toArray();
        $user_details = UserDetails::where('user_id',$user_id)->first();
        // $year = '';
        $data = array(            
            "filter" => [
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" =>  $user_details->customerno,
                    "operator" =>  "and"
                ],
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => $user_details->ardivisionno,
                    "operator" => "and"
                ],
            ],
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
            $response_data['salesorderhistoryheader'][$key]['salesorderhistorydetail'] = $response_data1['salesorderhistorydetail'];
        };

        $response = ['success' => true , 'data' => [ 'data' => $response_data, 'year' => '','user' =>$user]];
        echo \json_encode($response);
    }

    // sales order detail
    public function getSalesOrderDetail(Request $request){
        $order_no = $request->order_no;
        $item_code = $request->item_code;
        $data = array(            
            "filter" => [
                [
                    "column" =>  "SalesOrderNo",
                    "type" =>  "equals",
                    "value" => $order_no,
                    "operator" =>  "and"
                ],
            ],
        );
        $sales_order_history_header = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
        $sales_order_header = $sales_order_history_header['salesorderhistoryheader'][0];

        $filter = [
            [
                "column" =>  "SalesOrderNo",
                "type" =>  "equals",
                "value" => $order_no,
                "operator" =>  "and"
            ],
        ];

        if($item_code != ""){
            $new_filter = [ 
                "column" =>  "ItemCode",
                "type" =>  "equals",
                "value" => $item_code,
                "operator" =>  "and"
            ];
            array_push($filter,$new_filter);
        }

        $data1 = array(            
            "filter" => $filter
        );
        $sales_order_history_detail = $this->SDEApi->Request('post','SalesOrderHistoryDetail',$data1);
        $sales_order_detail = $sales_order_history_detail['salesorderhistorydetail'];
        foreach ($sales_order_detail as $key => $sales_order) {
            $data2 = array(            
                "filter" => [
                    [
                        "column" =>  "ItemCode",
                        "type" =>  "equals",
                        "value" => $sales_order['itemcode'],
                        "operator" =>  "and"
                    ],
                ],
            );
            $product_detail = $this->SDEApi->Request('post','Products',$data2);
            $sales_order_detail[$key]['product_details'] = $product_detail['products'];
        }
        $sales_order_header['sales_order_history_detail'] = $sales_order_detail;

        echo json_encode($sales_order_header);
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
