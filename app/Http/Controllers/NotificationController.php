<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Models\Notification;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\SalesPersons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Backend\DashboardController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class NotificationController extends Controller
{
    public $user;
    public $customer;
    public $isManager;
    public $superAdmin;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            $this->customer = Auth::user();
            $this->superAdmin = DashboardController::SuperAdmin($this->user);            
            return $next($request);
        });
    }
    
    public function index()
    {

    }

    public function create($data)
    {
        Notification::create($data);
        return true;
    }

    public function getNotifications(Request $request){
        $customer   =   $this->customer;
        $user       =   $this->user;   
        $type       = ''; 
        $notification = [];
        if($this->superAdmin){
            $notification   =   Notification::where('is_read',0)->where('to_user',0)->where('status',1)->orderBy('id','DESC')->get();
            $type           = 1;   
        }else if(!empty($user)){    
           $type               = 2;  
            $notification       =    User::leftjoin('user_sales_persons','users.id','=','user_sales_persons.user_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->leftjoin('notifications','users.id','=','notifications.from_user')->get();

                                //->where('users.id',$user->id)->orWhere('');
            
        }
        $content = '';
        if(!empty($notification)){
            foreach($notification as $_notification){
                $_type = $_notification->type;
                $icon = '';
                $text = '';
                switch($_type):
                    case 'signup':
                        $icon = '<i class="bx bx-group"></i>';
                        // $text   = 'New Sign Up Request from Customer';
                        $text   = config('constants.notification.signup');
                        break;
                    case 'change-order':
                        $icon = '<i class="bx bx-cart-alt"></i>';
                        // $text   = 'New Change Order Request from Customer';
                        $text   = config('constants.notification.change_order');
                        break;
                    case 'contact-details':
                        $icon = '<i class="bx bx-file"></i>';
                        // $text   = 'New Contact details update request from Customer';
                        $text   = config('constants.notification.contact_details');
                        break;
                    case 'inventory-update':
                        $icon = '<i class="bx bx-user-pin"></i>';
                        // $text   = 'New Inventory update request from Customer';
                        $text   = config('constants.notification.inventory_update');
                        break;    
                endswitch;   
              /*  $content .= '<a href="'.$_notification->action.'?notify='.$_notification->id.'" class="notify-item" target="_blank">
                                <div class="notify-thumb">'.$icon.'</div>
                                <div class="notify-text"><p>'.$text.'</p></div>
                            </a>'; */

                $content .= '<a class="dropdown-item" href="'.$_notification->action.'?notify='.$_notification->id.'" target="_blank">
                                <div class="d-flex align-items-center">
                                    <div class="notify bg-light-primary text-primary">'. $icon .'</div>
                                    <div class="flex-grow-1">                                    
                                        <p class="msg-info">'.$text.'</p>
                                    </div>
                                </div>
                            </a>';   

            }
        }
        $data  = array('type' => $type,'html' => $content,'count' => count($notification));
        //print_r($notification);
        //die;
        return Response::json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $user = User::find($id);
        if (!is_null($user)) {            
            $user->delete();
        }
        // session()->flash('success', 'User has been deleted !!');
        session()->flash('success', config('constants.customer_delete.confirmation_message'));
        return back();
    }


    /* bottom notifications */
    public function getBottomNotifications(){
        // <div id="bottom_notification_disp"></div>  
        if(auth('admin')->check()) {
            if($this->superAdmin){
                // Super admin
                $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
            } else {
                // Manager
                $notifications = User::leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->leftjoin('user_details','user_details.user_id','=','users.id')
                                    ->leftjoin('notifications','users.id','=','notifications.from_user')->where('to_user', 0)->where('status',1)->where('is_read',0)->get();
            }
            // $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
        } else {
            $user_id = Auth::user()->id;
            $notifications =  DB::select("SELECT * FROM `notifications` where to_user = $user_id and status = 1 and is_read = 0");
        }
        // $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
        if(!empty($notifications)){
            $notification_code = View::make("components.bottom-notification-component")
            ->with("notifications", $notifications)
            ->render();
            $response = ['success' => true,'notification_code' => $notification_code,'notifications_all'=> $notifications];
            echo json_encode($response);
            die();
        } else {
            $response = ['success' => false];
            echo json_encode($response);
            die();
        }
    }

    public function getNewBottomNotifications(){
        // $start_time = date('Y-m-d h:i').':00';
        // $end_time = date('Y-m-d h:i').':59';
        // $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0 and created_at >= '".$start_time."' and created_at <= '".$end_time."'");
        if(auth('admin')->check()) {
            if($this->superAdmin){
                // super admin
                $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
                $notifications_all =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");    
            } else {
                // manager 
                $notifications = User::leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->leftjoin('user_details','user_details.user_id','=','users.id')
                                    ->leftjoin('notifications','users.id','=','notifications.from_user')->where('to_user', 0)->where('status',1)->where('is_read',0)->get();
                $notifications_all = User::leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->leftjoin('user_details','user_details.user_id','=','users.id')
                                    ->leftjoin('notifications','users.id','=','notifications.from_user')->where('to_user', 0)->where('status',1)->where('is_read',0)->get();             
            }
            // $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
            // $notifications_all =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");    
        } else{
            $user_id = Auth::user()->id;
            $notifications =  DB::select("SELECT * FROM `notifications` where to_user = $user_id and status = 1 and is_read = 0");
            $notifications_all =  DB::select("SELECT * FROM `notifications` where to_user = $user_id and status = 1 and is_read = 0");
        }
        // $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
        // $notifications_all =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
        $notification_all_code = View::make("components.bottom-notification-component")
        ->with("notifications", $notifications_all)
        ->render();
        if(!empty($notifications)){
            $notification_message_code = "";
            foreach($notifications as $notification){
            $notification_message_code .= View::make("components.bottom-notification-message")
                ->with("title", $notification->type)
                ->with("desc", $notification->text)
                ->with("icon", '')
                ->with("time", $notification->created_at)
                ->with("link", $notification->action)
                ->with("id", $notification->id)
                ->render();
            }
            $response = ['success' => true,'notification_message_code' => $notification_message_code,'new_notifications'=> $notifications,'all_notifications' => $notifications_all,'all_notification_code' => $notification_all_code];
            echo json_encode($response);
            die();
        } else {
            $response = ['success'=> false];
            echo json_encode($response);
            die();
        } 
    }
    public function changeNotificationStatus(Request $request){
        $id  = $request->id;
        $notification = Notification::where('id',$id)->first();
        if($notification){
            $notification->is_read = 1;
            $notification->status = 0;
            $notification->save();
            $response = ['success' => true];
            echo json_encode($response);
            die();
        }
    }
}
