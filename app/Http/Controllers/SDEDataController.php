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

ini_set('max_execution_time', 300);

class SDEDataController extends Controller
{

    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
    }

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

        $response_data   = $this->SDEApi->Request('post','CustomerSalesHistory',$data);
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
                ],
                "offset" => 1,
                "limit" => 5,
            );
            $response_data   = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
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
        // foreach($response_data['salesorderhistoryheader'] as $key => $res){
        //     $data1 = array(            
        //         "filter" => [
        //             [
        //                 "column" => "SalesOrderNo",
        //                 "type" => "equals",
        //                 "value" => $res['salesorderno'],
        //                 "operator" => "and"
        //             ],
        //         ]
        //     );  
        //     $response_data1   = $this->SDEApi->Request('post','SalesOrderHistoryDetail',$data1);
        //     $response_data['salesorderhistoryheader'][$key]['salesorderhistorydetail'] = $response_data1['salesorderhistorydetail'];
        // };
        $table_code = View::make("components.datatabels.dashboard-invoice-component")
        ->with("invoices", $response_data['salesorderhistoryheader'])
        ->render();
        
        $response['table_code'] = $table_code;
        echo json_encode($response);
        die();
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

        $sales_order_history_detail = $this->SDEApi->Request('post','SalesOrders',$data);
        $sales_order_detail = $sales_order_history_detail['salesorders'];
        if(!empty($sales_order_detail)){
            foreach ($sales_order_detail as $key => $sales_order) {
                $sales_order_detail[$key]['product_details'] = $sales_order['details'];
            }
        }
        $sales_order_header['sales_order_history_detail'] = $sales_order_detail;
        $user = User::find(Auth::user()->id);
        $response = ['success' => true, 'data' => [ 'data' => $sales_order_header,'user' => $user ],'error' => []];
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

        $response   = $this->SDEApi->Request('post','CustomerItemHistory',$data);
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

        $response   = $this->SDEApi->Request('post','InvoiceHistoryHeader',$data);
        echo \json_encode($response);
    }

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
        // dd($response);
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
        // dd($response);
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
        // dd($response);
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
        // dd($response);
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
        // dd($response);
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
        // dd($response);
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
        // dd($response);
    }
    
    public function getSalesOrderHistoryHeader(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
        // echo \json_encode($response);
        // dd($response);
    }

    public function getSalespersons(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('get','Salespersons',$data);
        // echo \json_encode($response);
        // dd($response);
    }
    
    public function getVendors(){
        $data = array(            
            "offset" => 1,
            "limit" => 5
        );

        $response   = $this->SDEApi->Request('post','Vendors',$data);
        // echo \json_encode($response);
        // dd($response);
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
        // dd($user);
    }

    // public function profilePicUpload(Request $request){
    //     $user_id = Auth::user()->id;
    //     $user = User::find($user_id);
    //     $file = $request->file('photo_1');
    //     $image_name = 'test.'. $file->extension();
    //     $file->move(public_path('images'), $image_name);
    //     $path = 'images/'.$image_name;
    //     if($user){
    //         $user->profile_image = $path;
    //         $user->save(); 
    //     }
    // }
    public function accountEditUpload(Request $request){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user_details = UserDetails::where('user_id',$user_id)->first();
        // $password = $request->password;
        $data = $request->all();
        $validation_array = [];
        if($request->password){
            $validation_array = [
                'password' => 'required|confirmed',
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
                // image upload
                $file = $request->file('photo_1');
                $path = "";
                if($file){
                    // file delete 
                    if(Auth::user()->profile_image){
                        $image_path =str_replace('/','\\',Auth::user()->profile_image);
                        if(File::exists(public_path().'\\'.$image_path)){
                            File::delete(public_path().'\\'.$image_path);
                        }
                    }
                    
                    $user_name = str_replace(' ', '', Auth::user()->name);
                    $image_name = $user_name.'_'.date('Ymd_his').'.'. $file->extension();
                    $file->move(public_path('images'), $image_name);
                    $path = 'images/'.$image_name;
                }
                // database save
                if($file){
                    $user->profile_image = $path;
                }
                if($request->password != ""){
                    $user->password = Hash::make($request->password);
                }
                // update user detail information
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

    public function getCustomerOpenOrdersDetails(){
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
       if(empty($sales_order_history_header['salesorderhistoryheader'])){
            $response = ['success' => false, 'data' => [],'error' => ['No records found']];
            echo json_encode($response);
            die();
       }
        $sales_order_header = $sales_order_history_header['salesorderhistoryheader'][0];

        $filter =    array_push($filter,$new_filter);
        $data1 = array(            
            "filter" => $filter
        );
        $sales_order_history_detail = $this->SDEApi->Request('post','SalesOrderHistoryDetail',$data1);
        $sales_order_detail = $sales_order_history_detail['salesorderhistorydetail'];

       /* foreach ($sales_order_detail as $key => $sales_order) {
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
        }*/
        
        $sales_order_header['sales_order_history_detail'] = $sales_order_detail;


        //echo json_encode($sales_order_header);
        $user = User::find(Auth::user()->id);
        $response = ['success' => true, 'data' => [ 'data' => $sales_order_header,'user' => $user ],'error' => []];
        echo json_encode($response);
    }

    // change order save function 
    public function changeOrderPageSave(Request $request){
        $data = $request->input('data');
       // $customer_no = $request->input('customer_no');

        $customer_no   = $request->session()->get('customer_no');
        $sales_order_no = $request->input('sales_order_no');
        $ordered_date = $request->input('ordered_date');
        $user_id = Auth::user()->id;
        $user_details_id = UserDetails::where('customerno',$customer_no)
                           ->where('user_id',$user_id)
                           ->pluck('id')->first();


         
       // print_r($user_details_id);     die;
                  
                        
        if(!empty($data)){
            // $change_order_request = ChangeOrderRequest::where('user_id',$user_id)->where('order_no',$sales_order_no)->first(); 
            $is_insert = true;
            $change_order_requests = ChangeOrderRequest::where('user_id',$user_id)->where('order_no',$sales_order_no)->get()->toArray(); 
            foreach($change_order_requests as $order_request){
                if($order_request['request_status'] == 0){
                    $is_insert = false;
                }
            }
            // if(!$change_order_request || $change_order_request->request_status != 0){
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

            // Admin mail send work start
            $admin      = Admin::first();

            //if($admin){    
                $url    = env('APP_URL').'/admin/order/'.$sales_order_no.'/change/'.$change_order_request->id.'/'.$customer_no;

                // $details = array('mail_view' => 'emails.email-body', 
                //                 'subject'   => 'Change Order request', 
                //                 'url'       => $url);
                $email = Auth::user()->email;
                $details['mail_view']       =  'emails.email-body';
                $details['link']            =  $url;
                // $details['title']           =  "Change Order Request";   
                // $details['subject']         =  "New Change Order Request";
                $details['title']           =  config('constants.email.admin.change_order.title');   
                $details['subject']         =  config('constants.email.admin.change_order.subject');
                $body      = "<p>A customer with email address {$email} has requested an  order change request.<br/> Order Details</p>";
                $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Customer No: </span><span>'.$customer_no.'</span></p>';
                $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Sales Person-No: </span><span>'.$sales_order_no.'</span></p>';
                // $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Ordered Date: </span><span>'.$ordered_date.'</span></p><br/>';
                $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Ordered Date: </span><span>'.Carbon::createFromFormat('Y-m-d', $ordered_date)->format('m d, Y').'</span></p><br/>';
                $details['body'] = $body;  
                $admin_emails = env('ADMIN_EMAILS');
                $bcc_email = '';
                if($admin_emails != ''){                   
                    Mail::to($admin->email)->bcc(explode(',',$admin_emails))->send(new \App\Mail\SendMail($details));
                }else{
                    Mail::to($admin->email)->send(new \App\Mail\SendMail($params));
                }

                

                
           // }
            // Admin mail send work end


            $_notification = array( 'type'      => 'change-order',
                                    'from_user' => $user_id,
                                    'to_user'   => 0,
                                    'text'      => config('constants.notification.change_order'),
                                    'action'    => $url,
                                    'status'    => 1,
                                    'is_read'   => 0);                

            $notification = new NotificationController();                        
            $notification->create($_notification);

            echo json_encode(['success' => true]);
            die();
        }
    }
}
