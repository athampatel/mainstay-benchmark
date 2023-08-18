<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerExportController;
use App\Http\Controllers\PdfController;
use App\Models\Admin;
use App\Models\SalesPersons;
use App\Models\SearchWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

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
            abort(403,config('constants.admin_error_403'));
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
            $admin1 = Admin::orWhere('name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%')->orWhere('username','like','%'.$search.'%');
            if($order){
                $order_column = "admins.$order";
                $admin1->orderBy($order_column, $order_type);
            }
            $admins = $admin1->offset($offset)->limit(intval($limit))->get();
            $adminss = Admin::paginate(intval($limit));
        } else {
            if($order){
                $order_column = "admins.$order";
                $admins = Admin::orderBy($order_column, $order_type)->offset($offset)->limit(intval($limit))->get();
                $adminss = Admin::paginate(intval($limit));
            } else {
                $admins = Admin::offset($offset)->limit(intval($limit))->get();
                $adminss = Admin::paginate(intval($limit));
            }
        }

        $count_page = isset($_GET['page']) ? $_GET['page'] : 0;
        if($count_page == 0 || $count_page == 1){
            $old_counts = 0;
        } else {
            $old_counts = ($count_page - 1) * $limit;
        }
        
        $paginate = $adminss->toArray();
        $paginate['links'] = UsersController::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.admins.index', compact('admins','search','paginate','limit','searchWords','order','order_type','old_counts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            abort(403, config('constants.admin_error_403'));
        }
        $manager = '';        
        if($request->input('manager'))
            $manager = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('sales_persons.id',$request->input('manager'))
                                    ->get(['sales_persons.*'])->first();
        $roles  = Role::all();
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.admins.create', compact('roles','manager','searchWords'));
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
            abort(403, config('constants.admin_error_403'));
        }
        $file = $request->file('profile_picture1');
        $max_file_size = (int) self::parse_size(ini_get('post_max_size'));
        $this->validate(
            $request,
            [
                'name' => 'required|min:5',
                'email' => 'required|max:100|email|unique:admins',
                'username' => 'required|min:5|max:100|unique:admins',
                'password' => 'required|min:8',
                'profile_picture1' => 'sometimes|file|mimes:jpg,jpeg,png|max:'.$max_file_size,
            ],
            [
                'name.required' => 'The User Name field is required',
                'email.required' => 'The Email field is required',
                'username.required' => 'The User Account Name field is required',
                'password.required' => 'The Password field is required',
                'password.min' => 'The Password must be at least 8 characters.',
                'name.min' => 'The User Name must be at least 5 characters.',
                'username.min' => 'The User Account Name must be at least 5 characters.',
                'email.unique' => 'The Email has already been taken',
                'username.unique' => 'The User Account Name has already been taken',
                'profile_picture1.max' => 'The Profile Picture must not be greater than :max kilobytes. '
            ]
        );

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->phone_no = $request->phone_no;
        $admin->password = Hash::make($request->password);
        $path = "";
        if($file){
            if($admin->profile_path){
                $image_path =str_replace('/','\\',$admin->profile_path);
                if(File::exists(public_path().'\\'.$image_path)){
                    File::delete(public_path().'\\'.$image_path);
                }
            }
            
            $user_name = str_replace(' ', '', $admin->name);
            $image_name = $user_name.'_admin_'.date('Ymd_his').'.'. $file->extension();
            $file->move(public_path('images'), $image_name);
            $path = 'images/'.$image_name;
        }
        if($file){
            $admin->profile_path = $path;
        }
        $admin->save();
        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        if($request->input('send_password')){
            $to = $admin->email;
            $url    =  
            $details['subject'] = config('constants.email.admin.admin_create.subject');    
            $details['title']   = config('constants.email.admin.admin_create.title');
            // Please find your login credentials below: 
            $details['body']    = "$request->name, <br />Please find your login credentials below <br/> <strong>User Name: </strong/>$request->email.</br>Password: </strong/>".$request->password."<br/>";
            $details['mail_view']    = "emails.new-account-details";            
            $details['link']    = config('app.url').'/admin/login/';
            $is_local = config('app.env') == 'local' ? true : false;
            $test_emails = config('app.test_customer_email');
            if($is_local){
                UsersController::commonEmailSend($test_emails,$details);
                // Mail::bcc(explode(',',$test_emails))->send(new \App\Mail\SendMail($details));
            } else {
                try {
                    Mail::to($to)->send(new \App\Mail\SendMail($details));
                } catch (\Exception $e) {
                        Log::error('An error occurred while sending the mail: ' . $e->getMessage());
                        // echo "An error occurred while sending the mail: " . $e->getMessage();
                }
            }            
        }

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
            abort(403, config('constants.admin_error_403'));
        }

        $admin = Admin::find($id);
        $roles  = Role::all();
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.admins.edit', compact('admin', 'roles','searchWords'));
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
            abort(403, config('constants.admin_error_403'));
        }

        if ($id === 1) {
            session()->flash('error',config('constants.superadmin_update.error'));
            return back();
        }

        $admin = Admin::find($id);

        $max_file_size = (int) self::parse_size(ini_get('post_max_size'));

        $this->validate(
            $request,
            [
                'username' => 'required|min:5|max:100|unique:admins',
                'email' => 'required|max:100|email|unique:admins,email,' . $id,
                'password' => 'nullable|min:8',
                'profile_picture' => 'sometimes|file|mimes:jpg,jpeg,png|max:'.$max_file_size,
            ],
            [
                'email.required' => 'The Email field is required',
                'username.required' => 'The User Account Name field is required',
                'password.min' => 'The Password must be at least 8 characters.',
                'username.min' => 'The User Account Name must be at least 5 characters.',
                'email.unique' => 'The Email has already been taken',
                'username.unique' => 'The User Account Name has already been taken',
                'profile_picture1.max' => 'The Profile Picture must not be greater than :max kilobytes. '
            ]
        );

        $file = $request->file('profile_picture');
        $path = "";
        if($file){
            if($admin->profile_path){
                $image_path =str_replace('/','\\',$admin->profile_path);
                if(File::exists(public_path().'\\'.$image_path)){
                    File::delete(public_path().'\\'.$image_path);
                }
            }
            
            $user_name = str_replace(' ', '', $admin->name);
            $image_name = $user_name.'_admin_'.date('Ymd_his').'.'. $file->extension();
            $file->move(public_path('images'), $image_name);
            $path = 'images/'.$image_name;
        }
        if($file){
            $admin->profile_path = $path;
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->phone_no = $request->phone_no;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        $admin->roles()->detach();
        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

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
            abort(403, config('constants.admin_error_403'));
        }

        if ($id === 1) {
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
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.admins.profile',compact('profile_details','searchWords'));
    }
    
    // profile save
    public function adminProfileSave(Request $request){
        $user_id = Auth::guard('admin')->user()->id;
        $admin = Admin::where('id',$user_id)->first();
        $file = $request->file('photo_1');
        $path = "";
        if($file){
            if(Auth::guard('admin')->user()->profile_path){
                $image_path =str_replace('/','\\',Auth::guard('admin')->user()->profile_path);
                if(File::exists(public_path().'\\'.$image_path)){
                    File::delete(public_path().'\\'.$image_path);
                }
            }
            
            $user_name = str_replace(' ', '', Auth::guard('admin')->user()->name);
            $image_name = $user_name.'_admin_'.date('Ymd_his').'.'. $file->extension();
            $file->move(public_path('images'), $image_name);
            $path = 'images/'.$image_name;
        }
        if($file){
            $admin->profile_path = $path;
        }
        $admin->save();
        echo json_encode(['success' => true, 'data' => ['path' => '/'.$path,'message' => 'Profile Updated Successfully']]);
    }

    // export into excel
    public function ExportAllAdminsToExcel(){
        $admins = Admin::all()->toArray();
        $filename = "admins.csv";
        $header_array = array(
            'USERNAME',
            'NAME',
            'EMAIL'
        );
        $array_keys = array(
            'username',
            'name',
            'email'
        );
        return CustomerExportController::ExportExcelFunction($admins,$header_array,$filename,1,$array_keys);
    }
    
    // export into pdf
    public function ExportAllAdminsToPdf(){
        $admins = Admin::select('name','email','username')->get()->toArray();
        $name = 'admins.pdf';
        $array_keys = array(
            'name',
            'email',
            'username'
        );
        $array_headers = array(
            'NAME',
            'EMAIL',
            'USER NAME'
        );
        PdfController::generatePdf($admins,$name,$array_headers,$array_keys);
    }


    public static function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
        return round($size);
        }
    }
}
