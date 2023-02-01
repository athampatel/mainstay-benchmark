<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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

        // if (is_null($this->user) || !$this->user->can('dashboard.view')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        // }

        $user               = $this->user;
       
        //$this->user->can('dashboard.view')

        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";
        // die; 
        
        $total_roles        = count(Role::select('id')->get());
        $total_admins       = count(Admin::select('id')->get());
        $total_permissions  = count(Permission::select('id')->get());
        $total_customers    = count(User::select('id')->get());
        $active_customers   = count(User::select('id')->where('active','=',1)->get());
        return view('backend.pages.dashboard.index', compact('total_admins', 'total_roles', 'total_permissions', 'total_customers'));
    }

    public function getCustomers($userId = 0){
        if (!$userId)
            return false;
            
    }
}
