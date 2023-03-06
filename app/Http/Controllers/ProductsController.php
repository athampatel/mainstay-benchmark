<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductVendors;
use App\Models\ProductLine;
use Illuminate\Http\Request;
use App\Helpers\SDEApi;
use Illuminate\Support\Facades\Storage;
use File;

class ProductsController extends Controller
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
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products)
    {
        //
    }

    public static function getProducts($data = null){
        $_data      = array();
        $SDEApi     = new SDEApi();
        $products   = $SDEApi->Request('post','Products',$_data); 
        if(!empty($products)){
            Storage::disk('json')->put('products.json', json_encode($products));
        }
        return true;
    }

    public static function CheckUpdateProducts(){
        $_files = Storage::disk('json')->files();
        $path =  storage_path('server-data'); 
        foreach($_files as $key => $filename){
            if($filename == 'products.json'){
                $content = File::get($path.'/'.$filename);
                $data = json_decode($content,true);
                if(!empty($data)){
                    foreach($data as $key => $_products){
                        foreach($_products as $_product){
                            $product_line   = $_product['productline'];
                            $vendorno       = $_product['vendorno'];
                            $_category = ProductLine::where('product_line',$product_line)->pluck('id')->first();

                            if(!$_category){
                                $cat        = array('product_line'=> $product_line);
                                $category   = ProductLine::create($cat);
                                $_category  = $category->id;
                            }
                            $_vendor = ProductVendors::where('vendor_code',$vendorno)->pluck('id')->first();
                            if(!$_vendor){
                                $vendorinfo  = array('vendor_code'  => $vendorno,
                                                     'vendor_name'   => $_product['vendorname']);
                                $vendor = ProductVendors::create($vendorinfo);
                                $_vendor = $vendor->id;
                            }

                            $item = Products::where('itemcode',$_product['itemcode'])->first();

                            if(!$item){
                                $product = array('itemcode'         => $_product['itemcode'],
                                                'itemcodedesc'      => $_product['itemcodedesc'],
                                                'aliasitemno'       => $_product['aliasitemno'],
                                                'aliasitemdesc'     => $_product['aliasitemdesc$'],
                                                'quantityonhand'    => $_product['quantityonhand'],
                                                'vmiprice'          => 0.0,
                                                'unitprice'         => 0.0,
                                                'productlinedesc'   => $_product['productlinedesc'],
                                                'product_line_id'   => $_category,
                                                'vendor_id'         => $_vendor);
                                Products::create($product);                     
                            }else{
                                $item['itemcodedesc']       = $_product['itemcodedesc'];
                                $item['aliasitemno']        = $_product['aliasitemno'];
                                $item['aliasitemdesc']      = $_product['aliasitemdesc$'];
                                $item['quantityonhand']     = $_product['quantityonhand'];
                                $item['vmiprice']           = 0.0;
                                $item['unitprice']          = 0.0;
                                $item['productlinedesc']    = $_product['productlinedesc'].
                                $item['product_line_id']    = $_category;
                                $item['vendor_id']          = $_vendor;
                                $item->save();
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
}

