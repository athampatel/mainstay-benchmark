<?php

namespace App\Http\Controllers;

use App\Models\InvoicedOrders;
use Illuminate\Http\Request;
use App\Models\SaleOrders;
use App\Models\UserDetails;
use App\Models\User;
use App\Models\Products;
use App\Models\OrderDetails;
use App\Helpers\SDEApi;
use Illuminate\Support\Facades\Storage;
use File;
class InvoicedOrdersController extends Controller
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
     * @param  \App\Models\InvoicedOrders  $invoicedOrders
     * @return \Illuminate\Http\Response
     */
    public function show(InvoicedOrders $invoicedOrders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoicedOrders  $invoicedOrders
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoicedOrders $invoicedOrders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoicedOrders  $invoicedOrders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoicedOrders $invoicedOrders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoicedOrders  $invoicedOrders
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoicedOrders $invoicedOrders)
    {
        //
    }


    public static function getInvoiceOrders($_data = null){
       
        $_files = Storage::disk('json')->allFiles();
        $path =  storage_path('server-data'); 
        $current_time = time();
        foreach($_files as $key => $filename){
           // print_r($filename);
            if(strpos($filename,'SalesOrderHistoryHeader') !== false){     
                if(file_exists($path.'/'.$filename)) {         
                  $time = filectime($path.'/'.$filename);                
                  $file_created = $current_time - $time;
                  if($file_created <= 3600){
                        $content = File::get($path.'/'.$filename);
                        $data = json_decode($content,true);
                        if(!empty($data)){
                            if(isset($data['salesorderhistoryheader'])){
                                $salesorderhistoryheader = $data['salesorderhistoryheader'];
                            }elseif(isset($data['SalesOrderHistoryHeader'])){
                                $salesorderhistoryheader = $data['SalesOrderHistoryHeader'];
                            }
                            foreach($salesorderhistoryheader as $_order){
                                $customerno     = $_order['customerno'];
                                //$UserDetails    = UserDetails::where('customerno',$customerno)->get()->first();
                                $UserDetails = User::leftjoin('user_details','users.id','=','user_details.user_id')
                                               ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                               ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                               ->where('users.is_deleted',0)->where('users.active',1)->where('user_details.customerno',$customerno)->select(['user_details.*'])->get()->first();

                                $salesInfo['user_details_id'] = $UserDetails->id;
                                /* CODE TO REPLACE EMPTY SALE ORDERS */ 
                                if($_order['salesorderno'] == ''){
                                   $_order['salesorderno']  = $_order['invoiceno'];
                                   $_order['orderdate']     = $_order['invoicedate'];
                                }

                                $salesorderno   = $_order['salesorderno'];

                                $salesorder     = SaleOrders::where('salesorderno',$salesorderno)->where('user_details_id',$UserDetails->id)->get()->first();
                                $order_id       = 0;
                                $sale_key       = array('salesorderno',
                                                        'orderdate',
                                                        'shiptoname',
                                                        'shiptoaddress1',
                                                        'shiptoaddress2',
                                                        'shiptoaddress3',
                                                        'shiptocity',
                                                        'shiptostate',
                                                        'shiptozipcode',
                                                        'shipvia',
                                                        'taxablesalesamt',
                                                        'nontaxablesalesamt',
                                                        'freightamt',
                                                        'salestaxamt');
                                if(!$salesorder){                        
                                    foreach($sale_key as $key => $info){
                                        if(isset($_order[$info]))
                                            $salesInfo[$info]   = $_order[$info];
                                    }
                                    /* CODE TO REPLACE EMPTY SALE ORDERS */                                    
                                    $salesorder = SaleOrders::create($salesInfo);
                                    $order_id  = $salesorder->id;
                                }else{
                                    foreach($sale_key as $key => $info){
                                        if(isset($_order[$info]))
                                            $salesorder[$info]   = $_order[$info];
                                    }
                                    $salesorder->save();
                                    $order_id  = $salesorder->id;
                                }
                                
                               
                                /* ORDER ITEMS UPDATES */
                                $details_order  = $_order['details'];
                                $item_key       = array('quantityshipped',
                                                        'unitprice');

                                foreach($details_order as $_key => $item){
                                    $itemcode = $item['itemcode'];
                                    $product_item = Products::where('itemcode',$itemcode)->get()->first();

                                    if(!$product_item){
                                        $_product                       = array();
                                        $_product['itemcode']           = $item['itemcode'];
                                        $_product['itemcodedesc']       = $item['itemcodedesc'];
                                        $_product['aliasitemno']        = $item['aliasitemno'];
                                        $_product['aliasitemdesc']      = ' ';
                                        $_product['quantityonhand']     = 0;
                                        $_product['vmiprice']           = 0.0;
                                        $_product['unitprice']          = $item['unitprice'];
                                        $_product['productlinedesc']    = 0;
                                        $_product['product_line_id']    = 0;
                                        $_product['vendor_id']          = 0;
                                        $product_item = Products::create($_product);
                                    }

                                    $orderItem = array();
                                    $orderItem['sale_orders_id'] = $order_id;
                                    $orderItem['product_id'] = $product_item->id;
                                    $orderItem['quantitypurchased'] = $item['quantityshipped'];
                                    $orderItem['dropship'] = ($item['dropship'] == 'Y') ? 1 : 0;
                                    if($product_item && $item['quantityshipped'] > 0){
                                        $order_details = OrderDetails::where('sale_orders_id',$order_id)->where('product_id',$product_item->id)->get()->first();
                                        if(!$order_details){
                                            foreach($item_key as $key => $info){
                                                if(isset($item[$info]))
                                                    $orderItem[$info]   = $item[$info];
                                            } 
                                            OrderDetails::create($orderItem);
                                        }else{
                                            $order_details['quantitypurchased'] = $item['quantityshipped'];
                                            $order_details['dropship'] = ($item['dropship'] == 'Y') ? 1 : 0;
                                            foreach($item_key as $key => $info){
                                                if(isset($item[$info]))
                                                    $order_details[$info]   = $item[$info];
                                            } 
                                            $order_details->save();
                                        }
                                    }
                                }

                                $invoice_data = array('sale_orders_id' =>  $order_id);
                                $invoice_key = array('invoiceno','sale_orders_id','invoicedate','customerpono','termscode','ardivisionno','headerseqno');
                                $invoice = InvoicedOrders::where('invoiceno',$_order['invoiceno'])->where('sale_orders_id', $order_id)->get()->first();
                                if(!$invoice){                                   
                                    foreach($invoice_key as $key => $info){
                                        if(isset($_order[$info]))
                                            $invoice_data[$info]   = $_order[$info];
                                    }
                                    InvoicedOrders::create($invoice_data);
                                }else{
                                    foreach($invoice_key as $key => $info){
                                        if(isset($_order[$info]))
                                            $invoice[$info]   = $_order[$info];
                                    }
                                    $invoice->save();
                                }
                            }
                        }
                    }
                   unlink($path.'/'.$filename);
                }
               
            }            
        }

        return true;
    }

    public static function UpadateInvoiceOrders($data = null){



    }
}

