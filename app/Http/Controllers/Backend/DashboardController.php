<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AnalaysisExportRequest;
use App\Models\SalesPersons;
use App\Models\ChangeOrderRequest;
use App\Models\SignupRequest;
use App\Models\ChangeOrderItem;
use App\Models\SearchWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public $user;   

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public static function isManager($customer_id,$user){
        if(empty($user))
            return false;
        $lblusers = User::leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                    ->where('admins.id',$user->id)->where('users.id',$customer_id)->get(['users.id'])->count();

        return $lblusers;

    }
    public static function SuperAdmin($user){ 
        
        if(!isset($user->roles))
            return false;

        $currentRole    = $user->roles[0]->id;        
        $activePermisison = Permission::leftjoin('role_has_permissions','permissions.id','=','role_has_permissions.permission_id')
                                        ->leftjoin('roles','role_has_permissions.role_id','=','roles.id')
                                        ->select(['role_has_permissions.permission_id'])->where('role_has_permissions.role_id',$currentRole)->get()->count();

        $totalPermission = Permission::get()->count();
        
        if($activePermisison == $totalPermission)
            return true;
        else
            return false;    
    }
    public function index()
    {
        
        if(is_null($this->user)){
            return redirect()->route('admin.login');
        }
        if (!$this->user->can('dashboard.view')) {
            abort(403, config('constants.dashboard_error_403'));
        }

        $user               = 0;
        $total_roles        = 0;
        $total_admins       = 0;
        $total_permissions  = 0;
        $total_customers    = 0;
        $new_customers      = 0;
        $sales_persons      = 0;
        $vmi_customers      = 0;
        $change_request     = 0;
        $customer_export_count  = 0;

        if(self::SuperAdmin($this->user)){
            $user               = $this->user;
            $total_roles        = Role::select('id')->get()->count();
            $total_admins       = Admin::select('id')->get()->count();
            $total_permissions  = Permission::select('id')->get()->count();        
            // $total_customers    = User::select('id')->get()->count();   
            $total_customers    = UserDetails::select('customerno')->distinct()->count('customerno');
            // dd($total_customers);
            $new_customers      = SignupRequest::select('id')->where('status','=',0)->get()->count();
            $sales_persons      = SalesPersons::select('id')->get()->count();
            $vmi_customers      = User::leftjoin('user_details','users.id','=','user_details.user_id')
                                  ->where('user_details.vmi_companycode','!=','')->get()->count();
            $change_request     = ChangeOrderRequest::select('id')->where('request_status','=',0)->get()->count();            

            // get all the request downloads count
            $customer_export_count =  AnalaysisExportRequest::where('status',0)->get()->count();
        }else{   
            $manager = $this->user;
            $customers          =   User::leftjoin('user_details','users.id','=','user_details.user_id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('admins.id',$this->user->id);

            // $total_customers    = $customers->get(['users.id'])->count();            
            $total_customers    = $customers->get(['user_details.customerno'])->distinct()->count('customerno');            
            $new_customers      = $customers->where('active','=',0)->where('is_deleted','=',0)->get([ 'users.id'])->count();
            $vmi_customers      = $customers->where('active','=',1)->where('is_vmi','=',1)->get([ 'users.id'])->count();
            $change_request     = ChangeOrderRequest::select('change_order_requests.id')
                                    ->leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('request_status','=',0)
                                    ->where('admins.id',$manager->id)
                                    ->get()->count();
            //get all the request counts  
            $customer_export_count =  AnalaysisExportRequest::select('analaysis_export_requests.id')
                                        ->leftjoin('user_details','user_details.user_id','=','analaysis_export_requests.user_detail_id')
                                        ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                        ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                        ->leftjoin('admins','sales_persons.email','=','admins.email')
                                        ->where('status',0)->get()->count(); 
        }


        // search words
        $searchWords = SearchWord::where('type',1)->get()->toArray();

        // get welcome message
        $is_welcome = 0;
        if (session()->has('welcome')) {
            $is_welcome = session('welcome');
        }

        // dd($is_welcome);

        return view('backend.pages.dashboard.index', compact('total_admins',
                                                            'total_roles', 
                                                            'total_permissions', 
                                                            'total_customers',
                                                            'sales_persons',
                                                            'vmi_customers',
                                                            'new_customers',
                                                            'change_request',
                                                            'searchWords',
                                                            'customer_export_count',
                                                            'is_welcome'
                                                        )); 

    }
    public function getCustomers($userId = 0){
        if (!$userId)
            return false;
            
    }
}
