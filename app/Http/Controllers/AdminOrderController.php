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

        $change_order_request = ChangeOrderRequest::find($change_id);
        // if($change_order_request->request_status != 0){
        //     abort(403);
        // }
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
        $changed_items = ChangeOrderItem::where('order_table_id',$change_id)->get();
        return view('backend.pages.orders.change_request',compact('order_detail','changed_items','change_id')); 
    }

    public function changeOrderRequestStatus(Request $request){
        $data = $request->all();
        // get change order request
        $changeOrderRequest = ChangeOrderRequest::find($data['change_id']);
        if($changeOrderRequest){
            $message ="";
            if($data['status'] == 1){
                // Approve request 
                $changeOrderRequest->request_status = 1;
                $changeOrderRequest->status_detail = 'Approved';
                $changeOrderRequest->updated_by = Auth::guard('admin')->user()->id;
                $changeOrderRequest->save();
                $message = 'Change Order Request Approved';
            } else {
                // Decline request
                $changeOrderRequest->request_status = 2;
                $changeOrderRequest->status_detail = 'Declined';
                $changeOrderRequest->updated_by = Auth::guard('admin')->user()->id;
                $changeOrderRequest->save();
                $message = 'Change Order Request Declined';
            }

            $_notification = array( 'type'      => 'signup',
                                    'from_user'  => Auth::guard('admin')->user()->id,
                                    'to_user'  => $changeOrderRequest->user_id,
                                    'text'      => $message,
                                    'action'    => '',
                                    'status'    => 0,
                                    'is_read'   => 0);                

            $notification = new NotificationController();
            $notification->create($_notification);
            echo json_encode(['success' => true, 'data' => ['status' => $data['status']], 'error' => []]);
            die();
        } else {
            echo json_encode(['success' => false, 'data' => [] , 'error' =>'change request not found']);
            die();
        }
    }

    public function changeOrderRequestSync(Request $request){
        $data = $request->all();
        dd($data);
        // update change request api
        
    }
}
