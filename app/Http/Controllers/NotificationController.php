<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            $notification       =    User::leftjoin('user_sales_persons','users.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->leftjoin('notifications','users.id','=','notifications.from_user')->get();
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
                        $text   = config('constants.notification.signup');
                        break;
                    case 'change-order':
                        $icon = '<i class="bx bx-cart-alt"></i>';
                        $text   = config('constants.notification.change_order');
                        break;
                    case 'contact-details':
                        $icon = '<i class="bx bx-file"></i>';
                        $text   = config('constants.notification.contact_details');
                        break;
                    case 'inventory-update':
                        $icon = '<i class="bx bx-user-pin"></i>';
                        $text   = config('constants.notification.inventory_update');
                        break;    
                endswitch;   
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
        session()->flash('success', config('constants.customer_delete.confirmation_message'));
        return back();
    }

    public function getBottomNotifications(Request $request){
        // dd($request->is_notify);
        // $is_admin = Session()->get('is_admin');
        // if(auth('admin')->check() && $is_admin == 1) {
        if(auth('admin')->check() && $request->is_notify == '1') {
        //    if($request->is_notify == '1'){ 
                if($this->superAdmin){
                    $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
                    $notifications = json_decode(json_encode($notifications), true);
                } else {
                    $manager = $this->user;
                    $notifications = User::leftjoin('user_details','user_details.user_id','=','users.id')
                                        ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                        ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                        ->leftjoin('admins','sales_persons.email','=','admins.email')
                                        ->leftjoin('notifications','users.id','=','notifications.from_user')
                                        ->where('admins.id',$manager->id)
                                        ->where('to_user', 0)->where('status',1)->where('is_read',0)->get()->toArray();
                    // dd($notifications);
                }
        //    }
        } else {
            $user_id = Auth::user()->id;
            $notifications =  DB::select("SELECT * FROM `notifications` where to_user = $user_id and status = 1 and is_read = 0");
            $notifications = json_decode(json_encode($notifications), true);
        }
        // dd($notifications);
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

    public function getNewBottomNotifications(Request $request){
        // $is_admin = Session()->get('is_admin');
        // if(auth('admin')->check() && $is_admin == 1) {
        if(auth('admin')->check() && $request->is_notify == '1') {
            if($this->superAdmin){
                $notifications =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
                $notifications_all =  DB::select("SELECT * FROM `notifications` where to_user = 0 and status = 1 and is_read = 0");
                $notifications = json_decode(json_encode($notifications), true);    
                $notifications_all = json_decode(json_encode($notifications_all), true);    
            } else {
                $manager = $this->user;
                $notifications = User::leftjoin('user_details','user_details.user_id','=','users.id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->leftjoin('notifications','users.id','=','notifications.from_user')
                                    ->where('admins.id',$manager->id)
                                    ->where('to_user', 0)->where('status',1)->where('is_read',0)->get()->toArray();

                $notifications_all = User::leftjoin('user_details','user_details.user_id','=','users.id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->leftjoin('notifications','users.id','=','notifications.from_user')
                                    ->where('admins.id',$manager->id)
                                    ->where('to_user', 0)->where('status',1)->where('is_read',0)->get()->toArray();             
            }
        } else{
            $user_id = Auth::user()->id;
            $notifications =  DB::select("SELECT * FROM `notifications` where to_user = $user_id and status = 1 and is_read = 0");
            $notifications_all =  DB::select("SELECT * FROM `notifications` where to_user = $user_id and status = 1 and is_read = 0");
            $notifications = json_decode(json_encode($notifications), true);    
            $notifications_all = json_decode(json_encode($notifications_all), true);   
        }
        $notification_all_code = View::make("components.bottom-notification-component")
        ->with("notifications", $notifications_all)
        ->render();
        if(!empty($notifications)){
            $notification_message_code = "";
            foreach($notifications as $notification){
            $notification_message_code .= View::make("components.bottom-notification-message")
                ->with("title", $notification->type)
                ->with("desc", $notification->text)
                ->with("icon", $notification->icon_path)
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

    public function ClearAdminNotifications(Request $request){
        if(auth('admin')->check()) {
            if($this->superAdmin){
                $notifications =  Notification::where('to_user',0)->where('status',1)->where('is_read',0)->get();
            } else {
                $manager = $this->user;
                $notifications = User::leftjoin('user_details','user_details.user_id','=','users.id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->leftjoin('notifications','users.id','=','notifications.from_user')
                                    ->where('admins.id',$manager->id)
                                    ->where('to_user', 0)->where('status',1)->where('is_read',0)->get();
            }
        } else {
            $user_id = Auth::user()->id;
            $notifications =  Notification::where('to_user',$user_id)->where('status',1)->where('is_read',0)->get();
        }
        foreach($notifications as $notification){
            $notification->status = 0;
            $notification->is_read = 1;
            $notification->save();
        }
        echo json_encode(['success' => true]);
        die();
    }
}
