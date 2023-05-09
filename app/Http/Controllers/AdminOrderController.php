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
    public function getChangeOrderRequest($order_id,$change_id,$customerno){
        $change_request = ChangeOrderRequest::find($change_id);
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

        $SDEAPi = new SDEApi();
        $order_detail = $SDEAPi->Request('post','SalesOrderHistoryDetail',$data);
        if(!empty($order_detail['salesorderhistoryheader'])){
            $order_detail = $order_detail['salesorderhistoryheader'][0];
        } else {
            $order_detail = [];
        }
        $changed_items = ChangeOrderItem::where('order_table_id',$change_id)->get();
        return view('backend.pages.orders.change_request',compact('order_detail','changed_items','change_id','change_request')); 
    }

    public function changeOrderRequestStatus(Request $request){
        $data = $request->all();
        $changeOrderRequest = ChangeOrderRequest::find($data['change_id']);
        // dd()
        if($changeOrderRequest){
            $message ="";
            if($data['status'] == 1){
                $changeOrderRequest->request_status = 1;
                $changeOrderRequest->status_detail = 'Approved';
                $changeOrderRequest->updated_by = Auth::guard('admin')->user()->id;
                $changeOrderRequest->save();
                $message = config('constants.admin.change_order.approve');
            } else {
                $changeOrderRequest->request_status = 2;
                $changeOrderRequest->status_detail = 'Declined';
                $changeOrderRequest->updated_by = Auth::guard('admin')->user()->id;
                $changeOrderRequest->save();
                $message = config('constants.admin.change_order.decline');
            }
            $order_no = $changeOrderRequest->order_no;
            $url = config('app.url') ."change-order/info/$order_no";
            $_notification = array( 'type'      => 'Approved Change Request',
                                    'from_user'  => Auth::guard('admin')->user()->id,
                                    'to_user'  => $changeOrderRequest->user_id,
                                    'text'      => $message,
                                    'action'    => $url, // will be add the url
                                    'status'    => 1,
                                    'is_read'   => 0,
                                    'icon_path' => '/assets/images/svg/change_request_success_notification.svg'
                                );                

            $notification = new NotificationController();
            $notification->create($_notification);
            echo json_encode(['success' => true, 'data' => ['status' => $data['status']], 'error' => []]);
            die();
        } else {
            echo json_encode(['success' => false, 'data' => [] , 'error' =>config('constants.change_order_request.not_found')]);
            die();
        }
    }

    public function changeOrderRequestSync(Request $request){
        $data = $request->all();
    }
}
