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
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array(
            'INVOICE NUMBER',
            'CUSTOMER NAME',
            'CUSTOMER EMAIL',
            'CUSTOMER PO NUMBER',
            'TOTAL ITEMS',
            'PRICE',
            'DATE',
            'STATUS'
        ));

        foreach($response as $invoice) {
            fputcsv($handle, array(
                $invoice['INVOICE_NUMBER'],
                $invoice['CUSTOMER_NAME'],
                $invoice['CUSTOMER_EMAIL'],
                $invoice['CUSTOMER_PO_NUMBER'],
                $invoice['TOTAL_ITEMS'],
                $invoice['PRICE'],
                $invoice['DATE'],
                $invoice['STATUS'],
            )); 
        }
        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'invoice-detail.csv', $headers);
    }

    // invoice pdf
    public function exportInvoicePdf(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $response = self::exportInvoiceData($user_detail);
        $filename = "invoice-details.pdf";
        PdfController::generatePdf($response,$filename);
    }

    // open orders csv
    public function exportOpenCsv(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $response = self::exportOpenData($user_detail);
        $filename = "open-orders.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array(
            'SALES ORDER NUMBER',
            'CUSTOMER NAME',
            'CUSTOMER EMAIL',
            'TOTAL ITEMS',
            'PRICE',
            'DATE',
            'STATUS',
            'LOCATION'
        ));

        foreach($response as $invoice) {
            fputcsv($handle, array(
                $invoice['SALES_ORDER_NUMBER'],
                $invoice['CUSTOMER_NAME'],
                $invoice['CUSTOMER_EMAIL'],
                $invoice['TOTAL_ITEMS'],
                $invoice['PRICE'],
                $invoice['DATE'],
                $invoice['STATUS'],
                $invoice['LOCATION'],
            )); 
        }
        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'open-orders.csv', $headers);
    }

    // open orders pdf
    public function exportOpenPdf(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $response = self::exportOpenData($user_detail);
        $filename = "open-orders.pdf";
        PdfController::generatePdf($response,$filename);
    }
    
    // vmi csv
    public function exportVmiCsv(Request $request){
        // dd(config('constants.export_message.message'));
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $response = self::exportVmiData($user_detail);
        $filename = "Vmi-detail.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array(
            'CUSTOMER ITEM NUMBER',
            'BENCHMARK ITEM NUMBER',
            'ITEM DESCRIPTION',
            'VENDOR NAME',
            'QUANTITY ON HAND',
            'QUANTITY PURCHASED(YEAR)',
        ));

        foreach($response as $invoice) {
            fputcsv($handle, array(
                $invoice['CUSTOMER_ITEM_NUMBER'],
                $invoice['BENCHMARK_ITEM_NUMBER'],
                $invoice['ITEM_DESCRIPTION'],
                $invoice['VENDOR_NAME'],
                $invoice['QUANTITY_ON_HAND'],
                $invoice['QUANTITY_PURCHASED(YEAR)'],
            )); 
        }
        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'Vmi-detail.csv', $headers);
    }

    // vmi pdf
    public function exportVmiPdf(Request $request){
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $response = self::exportVmiData($user_detail);
        $filename = "Vmi-details.pdf";
        PdfController::generatePdf($response,$filename);
    }

    // datas

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
    public static function exportOpenData($user_detail){
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
            "offset" => 1,
            "limit" => 10,
        );
        $response   = $SDEApi->Request('post','SalesOrders',$data);
        $response = $response['salesorders'];
        $response_array = [];
        foreach($response as $openorders){
            $total = 0;
            $price = 0;
            foreach($openorders['details'] as $item){
                $total = $total + $item['quantityordered'];
                $price = $price + ($item['quantityordered'] * $item['unitprice']);
            }
            $data_array = [];
            $data_array['SALES_ORDER_NUMBER'] = $openorders['salesorderno'];
            $data_array['CUSTOMER_NAME'] = Auth::user()->name;
            $data_array['CUSTOMER_EMAIL'] = Auth::user()->email;
            $data_array['TOTAL_ITEMS'] = $total;
            $data_array['PRICE'] = $price;
            $data_array['DATE'] = Carbon::parse($openorders['orderdate'])->format('M d, Y');
            $data_array['STATUS'] = 'Open';
            $data_array['LOCATION'] = $openorders['shiptocity'];
            $response_array[]= $data_array;
        }

        return $response_array;
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
                $data_array['QUANTITY_PURCHASED(YEAR)'] = $product['quantitypurchased'];
                $response_array[]= $data_array;
            }
        }

        return $response_array;
    }

    /* test work start */
    function test(Request $request){
        // Fetch user detail from session
        $customer_no = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno', $customer_no)->first();
        $response = self::exportVmiData($user_detail);
        // Generate CSV contents in memory
        $contents = '';
        $delimiter = ',';
        $enclosure = '"';
        $escape = '\\';

        $header = array(
            'CUSTOMER ITEM NUMBER',
            'BENCHMARK ITEM NUMBER',
            'ITEM DESCRIPTION',
            'VENDOR NAME',
            'QUANTITY ON HAND',
            'QUANTITY PURCHASED(YEAR)',
        );
        $contents .= implode($delimiter, array_map(function($value) use ($enclosure, $escape) {
            return $enclosure . str_replace($enclosure, $escape . $enclosure, $value) . $enclosure;
        }, $header)) . "\n";

        foreach ($response as $invoice) {
            $row = array(
                $invoice['CUSTOMER_ITEM_NUMBER'],
                $invoice['BENCHMARK_ITEM_NUMBER'],
                $invoice['ITEM_DESCRIPTION'],
                $invoice['VENDOR_NAME'],
                $invoice['QUANTITY_ON_HAND'],
                $invoice['QUANTITY_PURCHASED(YEAR)'],
            );
            $contents .= implode($delimiter, array_map(function($value) use ($enclosure, $escape) {
                return $enclosure . str_replace($enclosure, $escape . $enclosure, $value) . $enclosure;
            }, $row)) . "\n";
        }

        // Stream CSV contents to HTTP response
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Vmi-detail.csv"',
        );
        $response = response()->stream(function() use ($contents) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, $contents);
            fclose($stream);
        }, 200, $headers);

        return $response;
    }
    /* test work end */
}


