<?php

namespace App\Http\Controllers;

use App\Models\SaleOrders;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\OrderDetails;
use App\Models\Users;
use App\Models\UserDetails;
use DB;

class SaleOrdersController extends Controller
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
     * @param  \App\Models\SaleOrders  $saleOrders
     * @return \Illuminate\Http\Response
     */
    public function show(SaleOrders $saleOrders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleOrders  $saleOrders
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleOrders $saleOrders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleOrders  $saleOrders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleOrders $saleOrders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleOrders  $saleOrders
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleOrders $saleOrders)
    {
        //
    }

    public function getRecentOrders($customer_no = '',$limit = 10, $page = 0,$pagiante = 0){

        $offset     = $page;       
        $orders     =   SaleOrders::leftjoin('order_details','sale_orders.id','order_details.sale_orders_id')
                                ->leftjoin('user_details','sale_orders.user_details_id','user_details.id')                               
                                ->leftjoin('users','user_details.user_id','users.id')                     
                                ->leftjoin('invoiced_orders','sale_orders.id','invoiced_orders.sale_orders_id')        
                                ->where('order_details.quantityshipped','>',0)
                                ->where('order_details.unitprice','>',0)
                                ->where('users.active',1)
                                ->where('user_details.customerno',$customer_no)
                                ->select(
                                    DB::raw('ROUND(sum(order_details.unitprice)) as total,
                                            sum(order_details.quantityshipped) as total_qty,                                            
                                            sale_orders.id,
                                            sale_orders.salesorderno,
                                            sale_orders.shiptoname,                                                  
                                            LOWER(sale_orders.shiptocity) as shiptocity,                                  
                                            sale_orders.shiptostate,                      
                                            sale_orders.orderdate,                      
                                            invoiced_orders.invoicedate,                      
                                            invoiced_orders.invoiceno,                      
                                            invoiced_orders.customerpono, 
                                            invoiced_orders.termscode,                      
                                            DATE_FORMAT(sale_orders.orderdate,"%b %d,%y") as date')
                                )
                                ->groupBy('sale_orders.id','sale_orders.orderdate','sale_orders.salesorderno','sale_orders.shiptoname','sale_orders.shiptoname','sale_orders.shiptostate','sale_orders.shiptocity','invoiced_orders.invoicedate','invoiced_orders.invoiceno','invoiced_orders.customerpono','invoiced_orders.termscode')
                                ->orderBy('sale_orders.orderdate', 'DESC');

        $counter        = $orders;
        $total_count    = $counter->get()->count();
        $paginate       = array();
        if($pagiante){
            $sale_order   = $orders->paginate($limit);
        }else{
            $sale_order  =   $orders->offset($offset)->limit($limit)->get()->toArray();
        }

        return array('total' => $total_count,'orders' => $sale_order,'current_page' => $page);
    }

    /*
    
    $results = DB::table('sale_orders')
                ->select(DB::raw('SUM(order_details.unitprice) as total'), DB::raw('SUM(order_details.quantityshipped) as total_qty'), 'sale_orders.salesorderno', 'sale_orders.id', DB::raw('DATE_FORMAT(sale_orders.orderdate,"%b %d,%y") as date'), 'sale_orders.*')
                ->leftJoin('order_details', 'sale_orders.id', '=', 'order_details.sale_orders_id')
                ->leftJoin('user_details', 'sale_orders.user_details_id', '=', 'user_details.id')
                ->leftJoin('users', 'user_details.user_id', '=', 'users.id')
                ->where('order_details.quantityshipped', '>', 0)
                ->where('order_details.unitprice', '>', 0)
                ->where('users.active', 1)
                ->where('user_details.customerno', 'GEMWI00')
                ->groupBy('sale_orders.salesorderno')
                ->orderBy('sale_orders.orderdate', 'desc')
                ->get();
    */

    public function getSaleByYear($customer_no = '',$args = null){

       if($customer_no == '')
           return false;

        $current_year   = date('Y') - 1;

        $from   = isset($args['from']) ? $args['from'] : $current_year.'-01-01';
        $to     = isset($args['to']) ? $args['to'] : $current_year.'-12-31';
        $orders =   SaleOrders::leftjoin('order_details','sale_orders.id','order_details.sale_orders_id')
                             ->leftjoin('user_details','sale_orders.user_details_id','user_details.id')
                             ->leftjoin('users','user_details.user_id','users.id')                             
                             ->where('order_details.quantityshipped','>',0)
                             ->where('users.active',1)
                             ->where('user_details.customerno',$customer_no)
                             ->whereBetween('sale_orders.orderdate', [$from, $to])
                             ->select(   
                                DB::raw('ROUND(sum(order_details.unitprice)) as total'), 
                                //DB::raw('sale_orders.orderdate'), 
                                DB::raw("DATE_FORMAT(sale_orders.orderdate,'%b') as month"),
                                DB::raw("DATE_FORMAT(sale_orders.orderdate,'%m') as monthKey")
          
                            )
        ->groupBy('month', 'monthKey')
        ->orderBy('monthKey', 'ASC')
        ->get()->toArray();
        return $orders;
    }

    public function getSaleByCategory($customer_no = '',$year = ''){
        if($year == '')
            $year   = date('Y') - 1;        

        $from   = $year.'-01-01';
        $to     = $year.'-12-31';

        $orders     =   SaleOrders::leftjoin('order_details','sale_orders.id','order_details.sale_orders_id')
                                ->leftjoin('user_details','sale_orders.user_details_id','user_details.id')
                                ->leftjoin('users','user_details.user_id','users.id')                             
                                ->leftjoin('products','order_details.product_id','products.id')                             
                                ->leftjoin('product_lines','products.product_line_id','product_lines.id')    
                                ->where('order_details.quantityshipped','>',0)
                                ->where('order_details.unitprice','>',0)
                                ->where('users.active',1)
                                ->where('user_details.customerno',$customer_no)
                                ->whereBetween('sale_orders.orderdate', [$from, $to])
                                ->where('product_lines.product_line','!=','')
                                ->select(
                                    DB::raw('ROUND(sum(order_details.unitprice)) as value,
                                             sum(order_details.quantityshipped) as total_qty,  
                                             product_lines.product_line as label')
                                )
                                ->groupBy('product_lines.product_line')
                                ->orderBy('product_lines.product_line', 'ASC');
       
        return $sale_order  =   $orders->get()->toArray();       

       // return array('total' => $total_count,'orders' => $sale_order,'current_page' => $page);
    }

    public function getOrderDetails($customerNo = 0,$sale_order = 0){
        if(!$customerNo || $sale_order)
           return false;
        dd($sale_order);
        $order     =    SaleOrders::leftjoin('invoiced_orders','sale_orders.id','invoiced_orders.sale_orders_id')
                                    ->leftjoin('user_details','sale_orders.user_details_id','user_details.id')                               
                                    ->leftjoin('users','user_details.user_id','users.id')                     
                                    ->where('users.active',1)
                                    ->where('user_details.customerno',$customerNo)
                                    ->where('sale_orders.salesorderno',$sale_order)
                                    ->select(['invoiced_orders.invoiceno','invoiced_orders.invoicedate','invoiced_orders.headerseqno','invoiced_orders.customerpono','invoiced_orders.termscode','invoiced_orders.ardivisionno','sale_orders.*'])->get()->first()->toArray();
        $order['items']  = array();
        if(!empty($order)){
            $items     =   OrderDetails::leftjoin('products','order_details.product_id','products.id')
                                          ->leftjoin('product_lines','products.product_line_id','product_lines.id')
                                          ->leftjoin('product_vendors','products.vendor_id','product_vendors.id')
                                          ->where('order_details.sale_orders_id',$order['id'])
                                          ->select(['order_details.quantitypurchased','order_details.quantityshipped','order_details.dropship','order_details.unitprice','product_lines.product_line','product_vendors.vendor_name','product_vendors.vendor_code','products.itemcode','products.itemcodedesc','products.aliasitemno','products.aliasitemdesc','products.productlinedesc'])->get()->toArray();

            $order['items']  = $items;
        }
       
        return $order;
    }
}
