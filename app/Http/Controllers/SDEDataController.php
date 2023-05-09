<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SDEApi;
use App\Models\Admin;
use App\Models\ChangeOrderItem;
use App\Models\ChangeOrderRequest;
use App\Models\User;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\NotificationController;
use App\Models\ApiData;
use App\Models\ApiType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Backend\AdminsController;

ini_set('max_execution_time', 300);

class SDEDataController extends Controller
{
    public function getCustomerSalesHistory(Request $request){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $customer_no   = $request->session()->get('customer_no');
        $user_details = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        $type = ApiType::where('name','CustomerSalesHistory')->first();
        $is_api_data = ApiData::where('customer_no',$user_details->customerno)->where('type', $type->id)->first();
        $is_fetch_data = true;
        if($is_api_data){
            $time_now = date('Y-m-d h:i:s');
            $update_time = $is_api_data->updated_at->diffInMinutes($time_now);
            if($update_time <= 30){
                $is_fetch_data = false;
            }
        }
        $year = date('Y');
        if($is_fetch_data){
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
                    "value" =>  "2022",
                    "operator" =>  "and"
                ]
            ]
        );

        $SDEAPi = new SDEApi();
        $response_data   = $SDEAPi->Request('post','CustomerSalesHistory',$data);
        $is_api_data = ApiData::where('customer_no',$user_details->customerno)->where('type', $type->id)->first();
            if($is_api_data){
                $is_api_data->data = json_encode($response_data);
                $is_api_data->updated_at = date('Y-m-d h:i:s'); 
                $is_api_data->save(); 
            } else {
                ApiData::create([
                    'customer_no' => $user_details->customerno,
                    'type' => $type->id,
                    'data' => json_encode($response_data)
                ]);
            }
        } else {
            $response_data = json_decode($is_api_data->data,true);
        }
        $response = ['success' => true , 'data' => [ 'data' => $response_data, 'year' => $year]];
        echo json_encode($response);
        die();
    }

    // invoice orders
    public function getCustomerInvoiceOrders(Request $request){
        $user_id = Auth::user()->id;
        $user = User::find($user_id)->toArray();
        $customer_no   = $request->session()->get('customer_no');
        $type = ApiType::where('name','salesorderhistoryheader')->first();
        $user_details = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        $is_api_data = ApiData::where('customer_no',$user_details->customerno)->where('type', $type->id)->first();
        $is_fetch_data = true;
        if($is_api_data){
            $time_now = date('Y-m-d h:i:s');
            $update_time = $is_api_data->updated_at->diffInMinutes($time_now);
            if($update_time <= 30){
                $is_fetch_data = false;
            }
        }
        if($is_fetch_data){
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
                    // [
                    //     "column" => "invoiceDate",
                    //     "type" => ">=",
                    //     "value" => '2023-01-01',
                    //     "operator" => "and"
                    // ],
                    // [
                    //     "column" => "invoiceDate",
                    //     "type" => "<=",
                    //     "value" => '2023-12-31',
                    //     "operator" => "and"
                    // ]
                ],
                "index" => "KSDEDESCENDING",
                "offset" => 1,
                "limit" => 5,
            );
            $SDEAPi = new SDEApi();
            // dd($data);
            $response_data   = $SDEAPi->Request('post','SalesOrderHistoryHeader',$data);
            // dd($response_data);
            $is_api_data = ApiData::where('customer_no',$user_details->customerno)->where('type', $type->id)->first();
            if($is_api_data){
                $is_api_data->data = json_encode($response_data['salesorderhistoryheader']);
                $is_api_data->updated_at = date('Y-m-d h:i:s'); 
                $is_api_data->save(); 
            } else {
                ApiData::create([
                    'customer_no' => $user_details->customerno,
                    'type' => $type->id,
                    'data' => json_encode($response_data['salesorderhistoryheader'])
                ]);
            }
        } else {
            $response_data['salesorderhistoryheader'] = json_decode($is_api_data->data,true);
        }
        // dd($response_data['salesorderhistoryheader']);
        $table_code = View::make("components.datatabels.dashboard-invoice-component")
        ->with("invoices", $response_data['salesorderhistoryheader'])
        ->render();
        $response['table_code'] = $table_code;
        // dd($response['table_code']);
        echo json_encode($response);
        die();
    }

    // sales order detail
    public function getSalesOrderDetail(Request $request){
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $is_change_order = MenuController::CheckChangeOrderAccess($user_id);
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
        $SDEAPi = new SDEApi();
        $sales_order_history_detail = $SDEAPi->Request('post','SalesOrders',$data);
        $sales_order_detail = $sales_order_history_detail['salesorders'];
        if(!empty($sales_order_detail)){
            foreach ($sales_order_detail as $key => $sales_order) {
                $sales_order_detail[$key]['product_details'] = $sales_order['details'];
            }
        }
        $sales_order_header['sales_order_history_detail'] = $sales_order_detail;
        $user = User::find(Auth::user()->id);
        $response = ['success' => true, 'data' => [ 'data' => $sales_order_header,'user' => $user, 'is_change_order' => $is_change_order ],'error' => []];
        echo json_encode($response);
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
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','CustomerItemHistory',$data);
        echo \json_encode($response);
    }


    public function getInvoiceOrderHeader($orderId = ''){
        $data = array(            
            "filter" => [
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => "00",
                    "operator" => "and"
                ],
                [
                    "column" => "CustomerNo",
                    "type" => "equals",
                    "value" => "GEMWI00",
                    "operator" => "and"
                ],
                [
                    "column" => "CustomerNo",
                    "type" => "equals",
                    "value" => "GEMWI00",
                    "operator" => "and"
                ],
            ]
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','InvoiceHistoryHeader',$data);
        echo \json_encode($response);
    }

    public function getAliasItems(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','AliasItems',$data);
    }

    public function getCustomers(){
        $data = array(            
            "offset" => 1,
            "limit" => 20,
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','Customers',$data);
    }

    public function getInvoiceHistoryDetail(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','InvoiceHistoryDetail',$data);
    }

    public function getInvoiceHistoryHeader(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','InvoiceHistoryHeader',$data);
    }
    
    public function getItemWarehouses(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','ItemWarehouses',$data);
    }
    
    public function getProducts(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','Products',$data);
    }
    
    public function getSalesOrderHistoryDetail(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','SalesOrderHistoryDetail',$data);
    }
    
    public function getSalesOrderHistoryHeader(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','SalesOrderHistoryHeader',$data);
    }

    public function getSalespersons(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('get','Salespersons',$data);
    }
    
    public function getVendors(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','Vendors',$data);
    }

    public function changeUserStatus($id){
        $user = User::find($id)->toArray();
        return view('Pages.user-active',compact('user'));
    }

    public function changeUserActive($id){  
        $user = User::find($id);
        $user->active = 1;
        $user->save();
        return redirect()->back();
    }

    public function changeUserCancel($id){
        $user = User::find($id);
    }

    public function accountEditUpload(Request $request){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user_details = UserDetails::where('user_id',$user_id)->first();
        $data = $request->all();
        $validation_array = [];
        // $max_file_size = (int) AdminsController::parse_size(ini_get('post_max_size'));
        if($request->password){
            $validation_array = [
                'password' => 'required|confirmed',
                // 'photo_1' => 'sometimes|file|mimes:jpg,jpeg,png|max:'.$max_file_size,
            ];
        }
        $validator = Validator::make($data, $validation_array);
        $errors = [];
        if($validator->fails()){
			$errors[] = $validator->errors()->all();
            echo json_encode(['success'=> false,'data' => [], 'error' => $errors]);
            die();
        } else {
            if($user){
                $file = $request->file('photo_1');
                $path = "";
                if($file){
                    if(Auth::user()->profile_image){
                        $image_path =str_replace('/','\\',Auth::user()->profile_image);
                        if(File::exists(public_path().'\\'.$image_path)){
                            File::delete(public_path().'\\'.$image_path);
                        }
                    }
                    
                    // $user_name = str_replace(' ', '', Auth::user()->name);
                    $user_name = str_replace(' ', '', Auth::user()->name);
                    $user_name = str_replace(',', '', $user_name);
                    $user_name = str_replace(':', '', $user_name);
                    
                    $image_name = $user_name.'_'.date('Ymd_his').'.'. $file->extension();
                    $file->move(public_path('images'), $image_name);
                    $path = 'images/'.$image_name;
                }
                if($file){
                    $user->profile_image = $path;
                }
                if($request->password != ""){
                    $user->password = Hash::make($request->password);
                }
                $user_details->customername = $request->acc_name;
                $user_details->addressline1 = $request->acc_address_line_1;
                $user_details->addressline2 = $request->acc_address_line_2;
                $user_details->city = $request->acc_city;
                $user_details->state = $request->acc_state;
                $user_details->zipcode = $request->acc_zipcode;
                $user_details->save();

                $user->name = $request->acc_name;
                $user->save();
                if($file) {
                    $response = ['path' => $user->profile_image];
                } else {
                    $response = [];
                }
                echo json_encode(['success' => true, 'data'=> [$response], 'error' => []]);
                die();
            } else {
                echo json_encode(['success' => false, 'data' => [] , 'error' => [config('constants.user_not_found')]]);
                die();
            }
        }
    }

    public function getCustomerOpenOrdersDetails(Request $request){
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
        $SDEAPi = new SDEApi();
        $sales_order_history_header = $SDEAPi->Request('post','SalesOrderHistoryHeader',$data);
       if(empty($sales_order_history_header['salesorderhistoryheader'])){
            $response = ['success' => false, 'data' => [],'error' => ['No records found']];
            echo json_encode($response);
            die();
       }
        $sales_order_header = $sales_order_history_header['salesorderhistoryheader'][0];
        $new_filter = [];
        $filter =    array_push($filter,$new_filter);
        $data1 = array(            
            "filter" => $filter
        );
        $SDEAPi = new SDEApi();
        $sales_order_history_detail = $SDEAPi->Request('post','SalesOrderHistoryDetail',$data1);
        $sales_order_detail = $sales_order_history_detail['salesorderhistorydetail'];
        $sales_order_header['sales_order_history_detail'] = $sales_order_detail;
        $user = User::find(Auth::user()->id);
        $response = ['success' => true, 'data' => [ 'data' => $sales_order_header,'user' => $user ],'error' => []];
        echo json_encode($response);
    }

    // change order save function 
    public function changeOrderPageSave(Request $request){
        $data = $request->input('data');
        $customer_no   = $request->session()->get('customer_no');
        $sales_order_no = $request->input('sales_order_no');
        $ordered_date = $request->input('ordered_date');
        $user_id = Auth::user()->id;
        $user_details_id = UserDetails::where('customerno',$customer_no)
                           ->where('user_id',$user_id)
                           ->pluck('id')->first();           
                        
        if(!empty($data)){
            $is_insert = true;
            $change_order_requests = ChangeOrderRequest::where('user_id',$user_id)->where('order_no',$sales_order_no)->get()->toArray(); 
            foreach($change_order_requests as $order_request){
                if($order_request['request_status'] == 0){
                    $is_insert = false;
                }
            }
            if(!$is_insert){
                echo json_encode(['success' => false,'error'=> config('constants.change_order_request.request_exsist')]);
                die();
            }

            $array = [
                'user_id'           => Auth::user()->id,
                'user_details_id'   => $user_details_id,
                'order_no'          => $sales_order_no,
                'ordered_date'      => $ordered_date,
            ];
            
            $change_order_request = ChangeOrderRequest::create($array);
            foreach($data as $da){
                $changeOrderItem = ChangeOrderItem::where('order_table_id',$change_order_request->id)->where('item_code',$da['itemcode'])->first();
                if(!$changeOrderItem){
                    $changeOrderItem = ChangeOrderItem::create([
                        'order_table_id' => $change_order_request->id ,
                        'item_code' => $da['itemcode'],
                        'existing_quantity' => $da['old_value'],
                        'modified_quantity' => $da['new_value'],
                        'order_item_price' => $da['unit_price']
                    ]);
                }
            }
                $admin      = Admin::first();
                $url    = config('app.url').'admin/customers/change-orders/'.$change_order_request->id;
                $email = Auth::user()->email;
                $details['mail_view']       =  'emails.email-body';
                $details['link']            =  $url;
                $details['title']           =  config('constants.email.admin.change_order.title');   
                $details['subject']         =  config('constants.email.admin.change_order.subject');
                $body      = "<p>A customer with email address {$email} has requested an  order change request.<br/> Order Details</p>";
                $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Customer-No: </span><span>'.$customer_no.'</span></p>';
                $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Sales Person-No: </span><span>'.$sales_order_no.'</span></p>';
                $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Ordered Date: </span><span>'.Carbon::createFromFormat('Y-m-d', $ordered_date)->format('m d, Y').'</span></p><br/>';
                $details['body'] = $body;  
                $admin_emails = config('app.admin_emails');
                $is_local = config('app.env') == 'local' ? true : false;
                if($is_local){
                    Mail::to($admin->email)->bcc(explode(',',$admin_emails))->send(new \App\Mail\SendMail($details));
                } else {
                    Mail::to($admin->email)->send(new \App\Mail\SendMail($details));
                }
                
            $_notification = array( 'type'      => 'Change Order',
                                    'from_user' => $user_id,
                                    'to_user'   => 0,
                                    'text'      => config('constants.notification.change_order'),
                                    'action'    => $url,
                                    'status'    => 1,
                                    'is_read'   => 0,
                                    'icon_path' => '/assets/images/svg/change_order_notification.svg'
                                );                

            $notification = new NotificationController();                        
            $notification->create($_notification);

            echo json_encode(['success' => true]);
            die();
        }
    }
}
