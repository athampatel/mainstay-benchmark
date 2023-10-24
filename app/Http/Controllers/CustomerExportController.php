<?php

namespace App\Http\Controllers;

use App\Helpers\SDEApi;
use App\Models\AnalaysisExportRequest;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerExportController extends Controller
{
    // Invoice request
    public function invoiceRequest(Request $request){
        $data = $request->all();
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $request_data = [
            "resource" => "SalesOrderHistoryHeader",
            "filter" => [
              [
                "column" => "CustomerNo",
                "type" => "equals",
                "value" => $user_detail->customerno,
                "operator" => "and",
              ],[
                "column" => "ARDivisionNo",
                "type" => "equals",
                "value" => $user_detail->ardivisionno,
                "operator" => "and",
              ]
            ]
        ];
        $log_data = self::exportLogData();
        $request_body = array_merge($log_data,$request_data); 
        $time_stamp = Carbon::now()->format('Ymd_his');
        $time_stamp = $time_stamp.'_'.$user_detail->id;
        $customer_export = AnalaysisExportRequest::create([
            'user_detail_id' => $user_detail->id,
            'customer_no' => $customer_no,
            'ardivisiono' => $user_detail->ardivisionno,
            'request_body' => json_encode($request_body),
            'resource' => 'SalesOrderHistoryHeader',
            'status' => 0,
            'unique_id' => $time_stamp,
            'type' => intval($data['type']),
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'year' => date('Y'),
            'is_analysis' => 0,
       ]);        

        if($customer_export){
            echo json_encode(['success' => true, 'message' => config('constants.export_message.message')]);
            die();
        }
    }
    
    // open order request
    public function openOrdersRequest(Request $request){
        $data = $request->all();
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $request_data = [
            "resource" => "SalesOrders",
            "filter" => [
              [
                "column" => "CustomerNo",
                "type" => "equals",
                "value" => $user_detail->customerno,
                "operator" => "and",
              ],[
                "column" => "ARDivisionNo",
                "type" => "equals",
                "value" => $user_detail->ardivisionno,
                "operator" => "and",
              ]
            ]
        ];
        $log_data = self::exportLogData();
        $request_body = array_merge($log_data,$request_data); 
        $time_stamp = Carbon::now()->format('Ymd_his');
        $time_stamp = $time_stamp.'_'.$user_detail->id;
        $customer_export = AnalaysisExportRequest::create([
            'user_detail_id' => $user_detail->id,
            'customer_no' => $customer_no,
            'ardivisiono' => $user_detail->ardivisionno,
            'request_body' => json_encode($request_body),
            'resource' => 'SalesOrders',
            'status' => 0,
            'unique_id' => $time_stamp,
            'type' => intval($data['type']),
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'year' => date('Y'),
            'is_analysis' => 0,
       ]);        

        if($customer_export){
            echo json_encode(['success' => true, 'message' => config('constants.export_message.message')]);
            die();
        }
    }

    // open order request
    public function vmiUserRequest(Request $request){
        $data = $request->all();
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $companycode = $user_detail['vmi_companycode'];
        $request_data = [
            "resource" => "Products",
            "companyCode"   => $companycode,
        ];
        $log_data = self::exportLogData();
        $request_body = array_merge($log_data,$request_data); 
        $time_stamp = Carbon::now()->format('Ymd_his');
        $time_stamp = $time_stamp.'_'.$user_detail->id;
        $customer_export = AnalaysisExportRequest::create([
            'user_detail_id' => $user_detail->id,
            'customer_no' => $customer_no,
            'ardivisiono' => $user_detail->ardivisionno,
            'request_body' => json_encode($request_body),
            'resource' => 'Products',
            'status' => 0,
            'unique_id' => $time_stamp,
            'type' => intval($data['type']),
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'year' => date('Y'),
            'is_analysis' => 0,
       ]);        

        if($customer_export){
            echo json_encode(['success' => true, 'message' => config('constants.export_message.message')]);
            die();
        }
    }


    public static function exportLogData(){
         return[
            "user" => "MainStay",
            "password" => "M@1nSt@y",
        ];
    }

    // invoice csv
    public function exportInvoiceCsv(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $response = self::exportInvoiceData($user_detail);
        $filename = "invoice-detail.csv";
        
        $header_array = array(
            'INVOICE NUMBER',
            'CUSTOMER NAME',
            'CUSTOMER EMAIL',
            'CUSTOMER PO NUMBER',
            'TOTAL ITEMS',
            'PRICE',
            'DATE',
            'STATUS'
        );
        return self::ExportExcelFunction($response,$header_array,$filename);
    }

    // invoice pdf
    // public function exportInvoicePdf(Request $request){
    //     $customer_no    = $request->session()->get('customer_no');
    //     $user_detail = UserDetails::where('customerno',$customer_no)->first();
    //     $is_local = config('app.env') == 'local' ? true : false;
    //     $email = $user_detail->email;
    //     if($is_local){
    //         $email = config('app.support_email');
    //     }
    //     $started_date = $request->started_date;
    //     $ended_date = $request->ended_date;
    //     // api request 
    //     $SDEApi = new SDEApi();
    //     $data = array(            
    //         "JobName" => "INVOICEHISTORY",
    //         "detail" => [
    //             [
    //                 "lineKey" => "000001",
    //                 "ReportSetting" =>  "SDE_VMI",
    //                 "EmailAddress" =>  $email,
    //                 "filter" =>  [
    //                     [
    //                         "column" => "CustomerNo",
    //                         "type" => "equals",
    //                         "value" => $user_detail->customerno,
    //                         "operator" => "and"
    //                     ],
    //                     [
    //                         "column" => "invoiceDate",
    //                         "type" => ">=",
    //                         "value" => $started_date,
    //                         "operator" => "and"
    //                     ],
    //                     [
    //                         "column" => "invoiceDate",
    //                         "type" => "<=",
    //                         "value" => $ended_date,
    //                         "operator" => "and"
    //                     ]
    //                 ]
    //             ]
    //         ],
    //     );
    //     $response   = $SDEApi->Request('post','ScheduledTask',$data);
    //     if(!empty($response)){
    //         if(isset($response['action']) && $response['action'] == 'executed') {
    //             return ['success' => true,'icon' => 'success','title' => 'Request Sent','message' => 'The sales report for invoiced orders has been sent. You will receive a notification once it has been generated.'];
    //         }
    //     }

    //     return ['success' => false, 'icon' => 'error', 'title' => 'Something Went Wrong','message' => 'Please try again in a few minutes.'];
    //     // $response = self::exportInvoiceData($user_detail);
    //     // $filename = "invoice-details.pdf";
    // }

    // open orders csv
    public function exportOpenCsv(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $data = self::exportOpenData($user_detail);
        $response = $data['data'];
        $filename = "open-orders_".time().".csv";
        $header_array = $data['headers'];
        return self::ExportExcelFunction($response,$header_array,$filename);
    }

    // open orders pdf
    public function exportOpenPdf(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $data = self::exportOpenData($user_detail);
        $response = $data['data'];
        $array_headers = $data['headers'];
        $array_keys = $data['keys'];
        $filename = "open-orders_".time().".pdf";
        PdfController::generatePdf($response,$filename,$array_headers,$array_keys);
    }
    
    // vmi csv
    public function exportVmiCsv(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $data = self::exportVmiData($user_detail);
        $filename = "Vmi-detail_".time().".csv";
        $response = $data['data'];
        $header_array = $data['headers'];
        return self::ExportExcelFunction($response,$header_array,$filename);
    }

    // vmi pdf
    public function exportVmiPdf(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $data = self::exportVmiData($user_detail);
        $response = $data['data'];
        $array_headers = $data['headers'];
        $array_keys = $data['keys'];
        $filename = "Vmi-details_".time().".pdf";
        PdfController::generatePdf($response,$filename,$array_headers,$array_keys);
    }

    // invoice data
    public static function exportInvoiceData($user_detail){
        $SDEApi = new SDEApi();
        $data = array(            
            "filter" => [
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" =>  $user_detail->customerno,
                    "operator" =>  "and"
                ],
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => $user_detail->ardivisionno,
                    "operator" => "and"
                ],
            ],
            "index" => "KSDEDESCENDING",
        );
        $response   = $SDEApi->Request('post','SalesOrderHistoryHeader',$data);
        $response = $response['salesorderhistoryheader'];

        $response_array = [];
        foreach($response as $invoice){
            $total = 0;
            $price = 0;
            foreach($invoice['details'] as $item){
                $total = $total + $item['quantityshipped'];
                $price = $price + ($item['quantityshipped'] * $item['unitprice']);
            }
            $data_array = [];
            $data_array['INVOICE_NUMBER'] = $invoice['invoiceno'];
            $data_array['CUSTOMER_NAME'] = Auth::user()->name;
            $data_array['CUSTOMER_EMAIL'] = Auth::user()->email;
            $data_array['CUSTOMER_PO_NUMBER'] = $invoice['customerpono'];
            $data_array['TOTAL_ITEMS'] = $total;
            $data_array['PRICE'] = $price;
            $data_array['DATE'] = Carbon::parse($invoice['invoicedate'])->format('M d, Y');
            $data_array['STATUS'] = 'Shipped';
            $response_array[]= $data_array;
        }

        return $response_array;
    }
    
    // open orders data
    public static function exportOpenData($user_detail = null){
        $SDEApi = new SDEApi();
        $data = array(            
            "index" => "KSDEDESCENDING",
            "filter" => [
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" =>  $user_detail->customerno,
                    "operator" =>  "and"
                ],
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => $user_detail->ardivisionno,
                    "operator" => "and"
                ],
                [
                    "column"=> "salesorderno",
                    "type"=> "begins",
                    "value"=> '10',
                    "operator"=> "and"
                ]
            ],
        );
        $response   = $SDEApi->Request('post','SalesOrders',$data);
        $response = $response['salesorders'];
        $response_array = [];
        $selected_customer = session('selected_customer');       
        $array_headers = array(
            'SALES ORDER NUMBER',
            'CUSTOMER P.O. NUMBER',
            'CUSTOMER NAME',
            'CUSTOMER EMAIL',
           // 'TOTAL ITEMS',
            'TOTAL VALUE',
            'ORDER DATE',
            'STATUS',
            'LOCATION',
            'ITEM NUMBER',
            //'ITEM DESCRIPTION',                        
            'QUANTITY ORDERED',
            'QUANTITY SHIPPED',
            'QUANTITY OPEN',
            //'UNIT PRICE',
            'PROMISE DATE'
        );
        $array_keys = array(
            'SALES_ORDER_NUMBER' => '',
            'CUSTOMER_PO_NUMBER' => '',
            'CUSTOMER_NAME' => '',
            'CUSTOMER_EMAIL' => '',
         //   'TOTAL_ITEMS' => '',
            'TOTAL_VALUE' => '',
            'ORDER_DATE' => '',
            'STATUS' => '',
            'LOCATION' => '',
            'ITEM_NUMBER' => '',
            //'ITEM_DESCRIPTION' => '', 
            'QUANTITY_ORDERED' => '',
            'QUANTITY_SHIPPED' => '',
            'QUANTITY_OPEN' => '',
            //'UNIT_PRICE' => '',
            'PROMISE_DATE' => ''
        );        
        foreach($response as $openorders){
            $total = 0;
            $price = 0;
            $item_details = '';

            $orderstatus = 'Open';
            /*if($openorders['orderstatus'] == 'N')
                $orderstatus = 'New'; */
                
           
            
            
            $data_array = $array_keys;  
            $salesorderno = $openorders['salesorderno'];
            $data_array['SALES_ORDER_NUMBER'] = $openorders['salesorderno'];
            $data_array['CUSTOMER_PO_NUMBER'] = $openorders['customerpono'];
            $data_array['CUSTOMER_NAME'] = $selected_customer['customername'];
            $data_array['CUSTOMER_EMAIL'] = $selected_customer['email'];
           // $data_array['TOTAL_ITEMS'] = $total;
            $data_array['TOTAL_VALUE'] = $price;
            $data_array['ORDER_DATE'] = Carbon::parse($openorders['orderdate'])->format('M d, Y');
            $data_array['STATUS'] = $orderstatus;
            $data_array['LOCATION'] = $openorders['shiptocity']; 
            $index  = 0;           
            if(isset($openorders['details']) && !empty($openorders['details'])){
                /* DUPLICATED LOOP FOR GETTONG COUNT HERE*/
                foreach($openorders['details'] as $item){
                    if($item['quantityordered'] > 0){
                        $total = $total + $item['quantityordered'];
                        $price = $price + ($item['quantityordered'] * $item['unitprice']);
                    }
                }
               // $data_array['TOTAL_ITEMS'] = $total;
                $data_array['TOTAL_VALUE'] = $price;

                foreach($openorders['details'] as $item){
                    if($item['quantityordered'] > 0){                        
                        if($index == 0){
                            $index_array = $data_array;
                        }else{
                            $index_array = $array_keys;
                        }    
                        $index_array['ITEM_NUMBER']      = $item['itemcode'];
                        //$index_array['ITEM_DESCRIPTION'] = $item['itemcodedesc'];
                        $index_array['QUANTITY_ORDERED'] = $item['quantityordered'];
                        $index_array['QUANTITY_SHIPPED'] = $item['quantityshipped'];
                        $index_array['QUANTITY_OPEN'] = $item['quantityordered'] - $item['quantityshipped'];
                        //$index_array['UNIT_PRICE']       = $item['unitprice'];
                        if(isset($item['promisedate']))
                            $index_array['PROMISE_DATE']     = Carbon::parse($item['promisedate'])->format('M d, Y');                        
                        $index++;                        
                        $response_array[]= $index_array;
                        $data_array = null;
                    }
                }
            }
            //$response_array[$salesorderno]['TOTAL_ITEMS'] = $total;
            //$response_array[$salesorderno]['TOTAL_PRICE'] = $price;
            if(!empty($data_array))          
                $response_array[]= $data_array;
        }
        return ['data' => $response_array, 'headers' => $array_headers, 'keys' => array_keys($array_keys)];
    }
    
    // vmi data
    public static function exportVmiData($user_detail){
        $SDEApi = new SDEApi();
        $companycode = $user_detail['vmi_companycode'];
        $data = array(            
            "companyCode"   => $companycode,
        );
        $response   = $SDEApi->Request('post','Products',$data);
        $response = $response['products'];
        $response_array = [];
        foreach($response as $product){
            $data_array = [];
            $string = $product['itemcode'];
            if (substr($string, 0, 1) !== '/') {
                $data_array['CUSTOMER_ITEM_NUMBER'] = '#8545478';
                $data_array['BENCHMARK_ITEM_NUMBER'] = $product['itemcode'];
                $data_array['ITEM_DESCRIPTION'] = $product['itemcodedesc'];
                $data_array['VENDOR_NAME'] = $product['vendorname'];
                $data_array['QUANTITY_ON_HAND'] = $product['quantityonhand'];
                $data_array['QUANTITY_PURCHASED (YEAR)'] = $product['quantitypurchased'];
                $response_array[]= $data_array;
            }
        }

        $array_headers = array(
            'CUSTOMER ITEM NUMBER',
            'BENCHMARK ITEM NUMBER',
            'ITEM DESCRIPTION',
            'VENDOR NAME',
            'QUANTITY ON HAND',
            'QUANTITY PURCHASED (YEAR)'
        );
        
        $array_keys = array(
            'CUSTOMER_ITEM_NUMBER',
            'BENCHMARK_ITEM_NUMBER',
            'ITEM_DESCRIPTION',
            'VENDOR_NAME',
            'QUANTITY_ON_HAND',
            'QUANTITY_PURCHASED (YEAR)'
        );

        return [ 'data' => $response_array, 'headers' => $array_headers, 'keys' => $array_keys];
    }

    // common function
    public static function ExportExcelFunction($response,$header_array,$filename,$type = 0,$array_keys = array()){
        $contents = '';
        $delimiter = ',';
        $enclosure = '"';
        $escape = '\\';

        $header = $header_array;
        $contents .= implode($delimiter, array_map(function($value) use ($enclosure, $escape) {
            return $enclosure . str_replace($enclosure, $escape . $enclosure, $value) . $enclosure;
        }, $header)) . "\n";

        foreach ($response as $res) {
            $row_array = array();
            if($type == 0){
                foreach($header_array as $harr){
                    $arkey = str_replace(' ','_',str_replace('.','',$harr));
                    if(isset($res[$arkey]))
                        array_push($row_array,$res[$arkey]);
                    else    
                        array_push($row_array,'');
                }
            } else {
                foreach($array_keys as $arkey1){
                    array_push($row_array,$res[$arkey1]);
                }
            }
            $row = $row_array;
            $contents .= implode($delimiter, array_map(function($value) use ($enclosure, $escape) {
                return $enclosure . str_replace($enclosure, "'", $value) . $enclosure;
            }, $row)) . "\n";
        }

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        );
        $response = response()->stream(function() use ($contents) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, $contents);
            fclose($stream);
        }, 200, $headers);

        return $response;
    }
}


 