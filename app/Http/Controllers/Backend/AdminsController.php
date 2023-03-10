<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SalesPersons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
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
        if (is_null($this->user) || !$this->user->can('admin.view')) {
            // abort(403, 'Sorry !! You are Unauthorized to view any admin !');
            abort(403,config('constants.admin_error_403'));
        }
        $limit = $request->input('limit');
        if(!$limit){
            $limit = 10;
        }  
        $search = $request->input('search');
        $admins = Admin::all();
        $adminss = Admin::paginate(intval($limit));
        $paginate = $adminss->toArray();
        $paginate['links'] = UsersController::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        // return view('backend.pages.admins.index', compact('admins'));
        return view('backend.pages.admins.index', compact('admins','search','paginate','limit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            // abort(403, 'Sorry !! You are Unauthorized to create any admin !');
            abort(403, config('constants.admin_error_403'));
        }
        $manager = '';        
        if($request->input('manager'))
            $manager = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('sales_persons.id',$request->input('manager'))
                                    ->get(['sales_persons.*'])->first();
        $roles  = Role::all();
        return view('backend.pages.admins.create', compact('roles','manager'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            // abort(403, 'Sorry !! You are Unauthorized to create any admin !');
            abort(403, config('constants.admin_error_403'));
        }

        // Validation Data
        // $request->validate([
        //     'name' => 'required|max:50',
        //     'email' => 'required|max:100|email|unique:admins',
        //     'username' => 'required|max:100|unique:admins',
        //     'password' => 'required|min:8',
        // ]);
        $this->validate(
            $request,
            [
                'name' => 'required|min:5',
                'email' => 'required|max:100|email|unique:admins',
                'username' => 'required|min:5|max:100|unique:admins',
                'password' => 'required|min:8',
            ],
            [
                'name.required' => 'The Name field is required',
                'email.required' => 'The Email field is required',
                'username.required' => 'The User Account Name field is required',
                'password.required' => 'The Password field is required',
            ]
        );

        // Create New Admin
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();
        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        if($request->input('send_password')){
            // $to = 'atham@tendersoftware.in';
            $to = $admin->email;
            $url    = 
            // $details['subject'] = "Your Login Credentials";    
            // $details['title']   = "Your Login Credentials";    
            $details['subject'] = config('constants.email.admin.admin_create.subject');    
            $details['title']   = config('constants.email.admin.admin_create.title');
            $details['body']    = "$request->name, <br />Please find you login credetials below <br/> <strong>User Name: </strong/>$request->email.</br>Password: </strong/>".$request->password."<br/>";
            $details['mail_view']    = "emails.new-account-details";
            
            $details['link']    = env('APP_URL').'/admin/login/';
            // \Mail::to($to)->send(new \App\Mail\SendMail($details));
            Mail::to($to)->send(new \App\Mail\SendMail($details));
        }

        

        // session()->flash('success', 'Admin has been created !!');
        session()->flash('success', config('constants.admin_create.confirmation_message'));
        return redirect()->route('admin.admins.index');
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
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            // abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
            abort(403, config('constants.admin_error_403'));
        }

        $admin = Admin::find($id);
        $roles  = Role::all();
        return view('backend.pages.admins.edit', compact('admin', 'roles'));
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
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            // abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
            abort(403, config('constants.admin_error_403'));
        }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        if ($id === 1) {
            // session()->flash('error', 'Sorry !! You are not authorized to update this Admin as this is the Super Admin. Please create new one if you need to test !');
            session()->flash('error',config('constants.superadmin_update.error'));
            return back();
        }

        // Create New Admin
        $admin = Admin::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:8',
        ]);


        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        $admin->roles()->detach();
        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        // session()->flash('success', 'Admin has been updated !!');
        session()->flash('success', config('constants.admin_update.confirmation_message'));
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
        if (is_null($this->user) || !$this->user->can('admin.delete')) {
            // abort(403, 'Sorry !! You are Unauthorized to delete any admin !');
            abort(403, config('constants.admin_error_403'));
        }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
        if ($id === 1) {
            // session()->flash('error', 'Sorry !! You are not authorized to delete this Admin as this is the Super Admin. Please create new one if you need to test !');
            session()->flash('error', config('constants.superadmin_delete.error'));
            return back();
        }

        $admin = Admin::find($id);
        if (!is_null($admin)) {
            $admin->delete();
        }

        session()->flash('success', config('constants.admin_delete.confirmation_message'));
        return back();
    }
    // get profile
    public function adminProfile(){
        $profile_details = Auth::guard('admin')->user();
        return view('backend.pages.admins.profile',compact('profile_details'));
    }
    // profile save
    public function adminProfileSave(Request $request){
        dd($request);
    }
}
