<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerExportController;
use App\Http\Controllers\PdfController;
use App\Models\SearchWord;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public $user;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('role.view')) {
            // abort(403, 'Sorry !! You are Unauthorized to view any role !');
            abort(403, config('constants.403.role.view'));
        }
        $limit = $request->input('limit');
        $order = $request->input('srorder');
        $order_type = $request->input('ortype');
        if(!$limit){
            $limit = 10;
        } 
        $offset     = isset($_GET['page']) ? $_GET['page'] : 0;
        if($offset > 1){
            $offset = ($offset - 1) * $limit;
        }
        if(isset($_GET['page']) && $_GET['page'] == 1){
            $offset = $offset - 1;
        }

        $search = $request->input('search');
        if($search){
            $roles1 = Role::where('name','like','%'.$search.'%');
            if($order){
                $order_column = "roles.$order";
                $roles1->orderBy($order_column, $order_type);
            }
                
            $roles = $roles1->offset($offset)->limit(intval($limit))->get();
            $roless = $roles1->paginate(intval($limit));
        } else {
            if($order){
                $order_column = "roles.$order";
                $roles = Role::orderBy($order_column,$order_type)->offset($offset)->limit(intval($limit))->get();
                $roless = Role::orderBy($order_column,$order_type)->paginate(intval($limit));
            } else {
                $roles = Role::offset($offset)->limit(intval($limit))->get();
                $roless = Role::paginate(intval($limit));
            }
        }
        $paginate = $roless->toArray();
        $paginate['links'] = UsersController::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);

        // Search words
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.roles.index', compact('roles','search','paginate','limit','searchWords','order','order_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('role.create')) {
            // abort(403, 'Sorry !! You are Unauthorized to create any role !');
            abort(403, config('constants.403.role.create'));
        }

        $all_permissions  = Permission::all();
        $permission_groups = User::getpermissionGroups();
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.roles.create', compact('all_permissions', 'permission_groups','searchWords'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('role.create')) {
            // abort(403, 'Sorry !! You are Unauthorized to create any role !');
            abort(403, config('constants.403.role.create'));
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        // Process Data
        $role = Role::create(['name' => $request->name, 'guard_name' => 'admin']);

        // $role = DB::table('roles')->where('name', $request->name)->first();
        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        // session()->flash('success', 'Role has been created !!');
        session()->flash('success', config('constants.admin.role.create'));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    public function edit(int $id)
    {
        if (is_null($this->user) || !$this->user->can('role.edit')) {
            // abort(403, 'Sorry !! You are Unauthorized to edit any role !');
            abort(403, config('constants.403.role.edit'));
        }

        $role = Role::findById($id, 'admin');
        $all_permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('backend.pages.roles.edit', compact('role', 'all_permissions', 'permission_groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if (is_null($this->user) || !$this->user->can('role.edit')) {
            // abort(403, 'Sorry !! You are Unauthorized to edit any role !');
            abort(403, config('constants.403.role.edit'));
        }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        if ($id === 1) {
            // session()->flash('error', 'Sorry !! You are not authorized to edit this role !');
            session()->flash('error', config('constants.403.role.edit'));
            return back();
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        $role = Role::findById($id, 'admin');
        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($permissions);
        }

        session()->flash('success', config('constants.admin.role.update'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('role.delete')) {
            // abort(403, 'Sorry !! You are Unauthorized to delete any role !');
            abort(403, config('constants.403.role.delete'));
        }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        if ($id === 1) {
            // session()->flash('error', 'Sorry !! You are not authorized to delete this role !');
            session()->flash('error', config('constants.403.role.delete'));
            return back();
        }

        $role = Role::findById($id, 'admin');
        if (!is_null($role)) {
            $role->delete();
        }

        session()->flash('success',config('constants.admin.role.delete'));
        return back();
    }

    public function ExportAllRolesInPdf(){
        $roles = Role::all()->toArray();
        $name = 'user-roles.pdf';
        PdfController::generatePdf($roles,$name);
    }

    public function ExportAllRolesInExcel(){
        $roles = Role::all()->toArray();
        $filename = "user-roles.csv";
        $header_array = array(
            'NAME',
            'GUARD NAME',
        );
        $array_keys = array(
            'name',
            'guard_name'
        );
        return CustomerExportController::ExportExcelFunction($roles,$header_array,$filename,1,$array_keys);
    }
}
