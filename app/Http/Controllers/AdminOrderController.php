<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Models\ChangeOrderItem;
use App\Models\ChangeOrderRequest;
use App\Models\UserDetails;

class AdminOrderController extends Controller
{
    public function __construct(SDEApi $SDEApi){
        $this->SDEApi = $SDEApi;
    }

    public function getChangeOrderRequest($order_id,$change_id,$customerno){

        $admin = Admin::first();
        Auth::guard('admin')->login($admin);

        $user_details = UserDetails::where('customerno',$customerno)->first();
        $data = array(            
            "filter" => [
                [
                    "column" =>  "SalesOrderNo",
                    "type" =>  "equals",
                    "value" => $order_id,
                    "operator" =>  "and"
                ],
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" => $customerno,
                    "operator" =>  "and"
                ],
                [
                    "column" =>  "ARDivisionNo",
                    "type" =>  "equals",
                    "value" => $user_details->ardivisionno,
                    "operator" =>  "and"
                ],
            ],
        );
        $order_detail = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
        if(!empty($order_detail['salesorderhistoryheader'])){
            $order_detail = $order_detail['salesorderhistoryheader'][0];
        } else {
            $order_detail = [];
        }

        // changed item details work start
        // ChangeOrderRequest::where('')
        // order_table_id
        $changed_items = ChangeOrderItem::where('order_table_id',$change_id)->get();
        // dd($changed_items);
        // changed item details work end
        return view('backend.pages.orders.change_request',compact('order_detail','changed_items','change_id')); 
    }
}
