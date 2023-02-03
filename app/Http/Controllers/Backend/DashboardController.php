<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SalesPersons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

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
    public static function SuperAdmin($user){
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
            abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        }

        $user               = 0;
        $total_roles        = 0;
        $total_admins       = 0;
        $total_permissions  = 0;
        $total_customers    = 0;
        $new_customers      = 0;
        $sales_persons      = 0;
        $vmi_customers      = 0;

        if(self::SuperAdmin($this->user)){
            $user               = $this->user;
            $total_roles        = Role::select('id')->get()->count();
            $total_admins       = Admin::select('id')->get()->count();
            $total_permissions  = Permission::select('id')->get()->count();        
            $total_customers    = User::select('id')->where('active','=',1)->get()->count();
            $new_customers      = User::select('id')->where('active','=',0)->where('is_deleted','=',0)->get()->count();
            $sales_persons      = SalesPersons::select('id')->get()->count();
            $vmi_customers      = User::select('id')->where('active','=',1)->where('is_vmi','=',1)->get()->count();
        }else{

            $customers          = User::leftjoin('user_sales_persons','users.id','=','user_sales_persons.user_id')
                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                ->leftjoin('admins','sales_persons.email','=','admins.email')
                                ->where('admins.id',$this->user->id);

            $total_customers    = $customers->get([ 'users.id'])->count();            
            $new_customers      = $customers->where('active','=',0)->where('is_deleted','=',0)->get([ 'users.id'])->count();
            $vmi_customers      = $customers->where('active','=',1)->where('is_vmi','=',1)->get([ 'users.id'])->count();

        }

        return view('backend.pages.dashboard.index', compact('total_admins',
                                                            'total_roles', 
                                                            'total_permissions', 
                                                            'total_customers',
                                                            'sales_persons',
                                                            'vmi_customers',
                                                            'new_customers'));

    }
    public function getCustomers($userId = 0){
        if (!$userId)
            return false;
            
    }
}
