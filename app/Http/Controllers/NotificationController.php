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
        if($this->superAdmin){
            $notification   =   Notification::where('is_read',0)->where('to_user',0)->orderBy('id','DESC')->get();
            $type           = 1;   
        }else if(!empty($user)){    
           $type                = 2;  
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
                switch($_type):
                    case 'signup':
                        $icon = '<i class="bx bx-group"></i>';
                        $text   = 'New Sign Up Request from Customer';
                        break;
                    case 'change-order':
                        $icon = '<i class="bx bx-cart-alt"></i>';
                        $text   = 'New Change Order Request from Customer';
                        
                        break;
                    case 'contact-details':
                        $icon = '<i class="bx bx-file"></i>';
                        $text   = 'New Contact details update request from Customer';
                        break;
                    case 'inventory-update':
                        $icon = '<i class="bx bx-user-pin"></i>';
                        $text   = 'New Inventory update request from Customer';
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
        session()->flash('success', 'User has been deleted !!');
        return back();
    }
}
