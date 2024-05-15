<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\SDEApi;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerExportController;
use App\Models\Admin;
use App\Models\SalesPersons;
use App\Models\User as Customer;
use App\User;
use App\Models\Customer as CustomerUnqiue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\DashboardController;
use App\Models\ChangeOrderRequest;
use App\Models\ChangeOrderItem;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PdfController;
use App\Models\CustomerMenu;
use App\Models\CustomerMenuAccess;
use App\Models\Notification;
use App\Models\SignupRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\SchedulerLogController;
use App\Models\UserDetails;
use App\Models\VmiInventoryRequest;
use App\Models\SearchWord;
use App\Models\AnalaysisExportRequest;
use Carbon\Carbon;
use App\Http\Controllers\Backend\AdminsController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\NewPasswordController;

class UsersController extends Controller
{
    public $user;  
    public $superAdmin; 
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            if(!$this->user){
                $unique_token = $request->segment(5); 
                $this->user = Admin::where('unique_token',$unique_token)->first();
            }
            $user_id = $request->segment(3); 
            $this->superAdmin = DashboardController::SuperAdmin($this->user);
            if(!$this->superAdmin && is_numeric($user_id)){
                $manager = DashboardController::isManager($user_id,$this->user);
                if(!$manager){
                    return abort('403');
                }
            }
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
        if(!Auth::guard('admin')->user()){
            return redirect()->route('admin.login');
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
        $user =  $this->user;    
        $manager = $request->input('manager');
        $type  = $request->input('type');
        $request_data = ['search' => $search,'type' => $type,'manager' => $manager];
        // dd($request_data);
        // $customers = CustomerUnqiue::whereHas('UserDetails.User', function ($query) use ($request_data) {

       // $usersIn = DB::table('users')->where('is_deleted', '0')->where('is_temp', '0')->pluck('id')->toArray();
        //dd($usersIn);
        $customers = CustomerUnqiue::whereHas('UserDetails', function ($query) use ($request_data,$user) {
            $query->join('users','users.id','=','user_details.user_id');
            $query->leftjoin('user_sales_persons','user_sales_persons.user_details_id','=','user_details.id')
                    ->leftjoin('sales_persons','sales_persons.id','=','user_sales_persons.sales_person_id');
            if($request_data['search'] != ""){                
                $query->where(function($query) use($request_data){
                        $query->orWhere('customerno','like','%'.$request_data['search'].'%')
                                ->orWhere('user_details.email','like','%'.$request_data['search'].'%')
                                ->orWhere('users.email','like','%'.$request_data['search'].'%')
                                ->orWhere('users.name','like','%'.$request_data['search'].'%')
                                ->orWhere('customerno','like','%'.$request_data['search'].'%')
                                ->orWhere('ardivisionno','like','%'.$request_data['search'].'%')
                                ->orWhere('sales_persons.name','like','%'.$request_data['search'].'%');
                });
            }
            $query->select('user_details.*','sales_persons.name');
            
            $query->where('users.is_deleted',0)->where('users.is_temp',0); //->whereNotNull('users.id');

           // $query->whereIn('user_details.user_id',$usersIn);

            if($request_data['type'] != "" && $request_data['type'] == 'vmi') {
                $query->where('user_details.vmi_companycode','!=','');
            }

            //$query->where('users.is_temp','=',0);

            if($request_data['type'] != "" && $request_data['type'] == 'new'){
                $query->where('users.active','=',0);
            }
            if($request_data['manager'] != "") {
                $query->leftjoin('admins','sales_persons.email','=','admins.email');
                $query->where('admins.id',$request_data['manager']);
            }
            if (!$this->superAdmin && !empty($user)){
                $query->leftjoin('admins','sales_persons.email','=','admins.email'); 
                $query->where('admins.id',$user->id);
            }
        })->with(['UserDetails.User','UserDetails.userSalesPerson.salesPerson'])->offset($offset)->limit($limit);

        // dd($customers);

        /* test working start */
        // $customers = Customer::whereHas('UserDetails', function ($query) use ($request_data) {
        //     $query->leftjoin('user_sales_persons','user_sales_persons.user_details_id','=','user_details.id')
        //             ->leftjoin('sales_persons','sales_persons.id','=','user_sales_persons.sales_person_id');
        //     if($request_data['search'] != ""){                
        //         $query->where(function($query) use($request_data){
        //                 $query->orWhere('customerno','like','%'.$request_data['search'].'%')
        //                         ->orWhere('user_details.email','like','%'.$request_data['search'].'%')
        //                         ->orWhere('customerno','like','%'.$request_data['search'].'%')
        //                         ->orWhere('ardivisionno','like','%'.$request_data['search'].'%')
        //                         ->orWhere('sales_persons.name','like','%'.$request_data['search'].'%');
        //         });
        //     }
        //     $query->select('user_details.*','sales_persons.name');
        //     $query->where('users.is_deleted',0);
        //     if($request_data['type'] != "" && $request_data['type'] == 'vmi') {
        //         $query->where('user_details.vmi_companycode','!=','');
        //     }
        //     if($request_data['type'] != "" && $request_data['type'] == 'new'){
        //         $query->where('users.active','=',0);
        //     }
        //     if($request_data['manager'] != "") {
        //         $query->leftjoin('admins','sales_persons.email','=','admins.email');
        //         $query->where('admins.id',$request_data['manager']);
        //     }
        //     if (!$this->superAdmin && !empty($user)){
        //         $query->leftjoin('admins','sales_persons.email','=','admins.email'); 
        //         $query->where('admins.id',$user->id);
        //     }
        // })->with(['UserDetails','UserDetails.userSalesPerson.salesPerson'])->offset($offset)->limit($limit);
        
        // dd($customers);
        /* test working end */
    // })->with(['UserDetails.User'])->offset($offset)->limit($limit);

       

        $userss = $customers->paginate(intval($limit));
        //$users =  $customers->get()->toJson();
        $users =  $customers->get()->toArray();


       /* echo "<pre>";
        print_r($users);
        echo "</pre>";
        die;    */
        
        //echo $customers->toSql();
        // dd($users);

        //dd($users);

        $print_users = $users;
        //echo $customers->toSql();
        // dd($users);        
       // die; 
        ///
        
        //select('users.id','users.email','users.profile_image','users.active','users.is_deleted','users.activation_token','users.is_vmi','users.name');
        $paginate = $userss->toArray();
        $paginate['links'] = self::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.users.index', compact('users','paginate','limit','search','searchWords','print_users','order_type','order'));
    }

    public static function customPagination($first_page,$last_page,$total,$per_page,$active_page,$link_path){
        $first_page = 1;
        $links =[];
        if($first_page == $last_page){
            $links[] = [
                "url" => "$link_path?page=1",
                "label" => "1",
                "active" => true
            ];
        } else {
            if($active_page == $first_page){
                $count1 = 1;
                for($i = $active_page; $i <= $last_page; $i++){
                    if($count1 <= 3){ 
                        $links[] = [
                            "url" => "$link_path?page=$i",
                            "label" => "$i",
                            "active" => $i == $active_page,
                        ];        
                    }
                    $count1++;
                }
            }
            if($active_page != $first_page && $active_page != $last_page){
                $links[] = [
                    "url" => "$link_path?page=".$active_page - 1,
                    "label" => $active_page - 1,
                    "active" => false,
                ];
                $links[] = [
                    "url" => "$link_path?page=".$active_page,
                    "label" => $active_page,
                    "active" => true,
                ];
                $links[] = [
                    "url" => "$link_path?page=".$active_page + 1,
                    "label" => $active_page + 1,
                    "active" => false,
                ];
            }
            if($active_page == $last_page){
                if($active_page - 2 < $first_page){ 
                    $links[] = [
                        "url" => "$link_path?page=".$active_page - 1,
                        "label" => $active_page - 1,
                        "active" => false,
                    ];
                    $links[] = [
                        "url" => "$link_path?page=".$active_page,
                        "label" => $active_page,
                        "active" => true,
                    ];
                } else {
                    $links[] = [
                        "url" => "$link_path?page=".$active_page - 2,
                        "label" => $active_page - 2,
                        "active" => false,
                    ];
                    $links[] = [
                        "url" => "$link_path?page=".$active_page - 1,
                        "label" => $active_page - 1,
                        "active" => false,
                    ];
                    $links[] = [
                        "url" => "$link_path?page=".$active_page,
                        "label" => $active_page,
                        "active" => true,
                    ];
                }
            }
        }
        return $links;
    }

    public function UserManagers(Request $request){
        $limit = $request->input('limit');
        
        if(!$limit){
            $limit = 10;
        }

        $offset     = isset($_GET['page']) ? $_GET['page'] : 0;
        $order = $request->input('srorder');
        $order_type = $request->input('ortype');
        if($offset > 1){
            $offset = ($offset - 1) * $limit;
        }
        
        if(isset($_GET['page']) && $_GET['page'] == 1){
            $offset = $offset - 1;
        }
        
        $search = $request->input('search');
        
        if($search) {   
            $managers1 = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                    ->orWhere('sales_persons.person_number','like','%'.$search.'%')
                    ->orWhere('sales_persons.name','like','%'.$search.'%')
                    ->orWhere('sales_persons.email','like','%'.$search.'%');
            if($order){
                $order_column = "sales_persons.$order";
                $managers1->orderBy($order_column,$order_type);
            }
            $managers = $managers1->offset($offset)
                    ->limit(intval($limit))
                    ->get(['sales_persons.*','admins.id as user_id']);
            $managerss = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                ->orWhere('sales_persons.person_number','like','%'.$search.'%')
                ->orWhere('sales_persons.name','like','%'.$search.'%')
                ->orWhere('sales_persons.email','like','%'.$search.'%')
                ->paginate(intval($limit));    
        } else {
            $managers1 = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email'); 
            if($order){
                $order_column = "sales_persons.$order";
                $managers1->orderBy($order_column, $order_type);
            }
            $managers = $managers1->offset($offset)
                    ->limit(intval($limit))
                    ->get(['sales_persons.*','admins.id as user_id']);
            $managerss = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                    ->paginate(intval($limit));
        }
        $print_managers = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                            ->get(['sales_persons.*','admins.id as user_id']);
        $paginate = $managerss->toArray();
        $paginate['links'] = UsersController::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        // search words
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.managers.index', compact('managers','search','paginate','limit','searchWords','print_managers','order','order_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $roles  = Role::all();
        $email  = '';
        if($request->input('email'))
            $email = $request->input('email');
        $constants = config('constants');
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.users.create', compact('roles','email','constants','searchWords'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postdata       = $request->all();
        // dd($postdata);
        $is_duplicate   = 0;
        $email_address  = '';
        $user_id = 0;
       
        if(!isset($postdata['create_user'])){
            $request->validate([
                'customername' => 'required|max:50',
                // 'customerno' => 'unique:user_details',
                'contactemail' => 'required|max:100|email',
                'salespersonno' => 'required|min:1',
            ]);
            $postdata['emailaddress'] = $postdata['contactemail'];            
            $response = $this->CreateCustomer($postdata);
            $email_address = $postdata['contactemail'];
            $user_id = $response['id'];
        } else {

            $duplicate      = array();
            $customer       = array();
            $create_user    =  $postdata['create_user'];
            $emails         = isset($postdata['contactemail']) ? $postdata['contactemail'] : $postdata['email'];
            foreach($create_user as $key => $value){
                $is_duplicate = 0;
                $email        = isset($emails[$key]) ? $emails[$key] : '';
                if(!in_array($email,$duplicate)){
                    $duplicate[]    = $email;
                    $email_address  = $email;
                }else{
                    $is_duplicate = 1;  
                }
                
              
                foreach($postdata as $data_key => $_val){
                    
                    if($data_key == '_token'){
                        $customer[$key][$data_key] = $_val;
                    }elseif(is_array($_val)){
                        foreach($_val as $_ind => $value){
                            $customer[$_ind][$data_key] = $value;
                        }
                    } else {
                        $customer[$key][$data_key] = $_val;
                    }
                }
            }
        //    dd($customer);
            if(!empty($customer)){
                foreach($customer as $insert_key => $_customer){
                    if(isset($_customer['customerno'])){
                        if(!$user_id){
                            $response = $this->CreateCustomer($_customer,$insert_key);
                            if($response['status'] != 0){
                                $user_id  = $response['id'];
                            }
                            if($request->input('send_password')){
                                $details['subject'] = config('constants.email.admin.customer_create.subject');
                                $details['title']   = config('constants.email.admin.customer_create.title');    
                                $details['body']    = "$request->name, <br />Please find your login credentials below <br/> <strong>User Name: </strong/>$request->email.</br>Password: </strong/>".$request->password."<br/>";
                                $details['mail_view']    = "emails.new-account-details";
                                $details['link']    = config('app.url').'/';
                                $customer_emails = config('app.test_customer_email');
                                $is_local = config('app.env') == 'local' ? true : false;
                                if($is_local){
                                    self::commonEmailSend($customer_emails,$details);
                                    // Mail::bcc(explode(',',$customer_emails))->send(new \App\Mail\SendMail($details));
                                } else {
                                    try {
                                        Mail::to($request->email)->send(new \App\Mail\SendMail($details));
                                    } catch (\Exception $e) {
                                        Log::error('An error occurred while sending the mail: ' . $e->getMessage());
                                        // echo "An error occurred while sending the mail: " . $e->getMessage();
                                    }
                                }
                            }
                        }else{
                            $res = UserController::CreateCucstomerDetails($_customer,$user_id);
                        }
                    }
                }
            }
        }   
        
        $user_request = SignupRequest::where('email',$email_address)->where('status',0)->get()->first();
        if(!empty($user_request)){
            $user_request['status'] = 1;
            $user_request->save();
        }


        $SchedulerLog = new SchedulerLogController();
        $SchedulerLog->CreateScheduler($user_id);
        
        $status  = isset($response['status']) ? $response['status'] : 0;
        $message = config('constants.admin_customer_create.success');
        session()->flash($status, $message);
        if($request->input('ajaxed')){
            echo json_encode(array('status' => $status,'message' => $message,'redirect' => route('admin.users.index')));
            die;
        }else{
            return redirect()->route('admin.users.index');
        }
    }

    public function CreateCustomer($postdata = null,$key = 0,$notify = 1){
        // dd($postdata);
        $response   = AuthController::CreateCustomer($postdata,1);
        $user       = isset($response['user']) ? $response['user'] : null;
        $message    = config('constants.admin_customer_create.mail.error');
        $status     = 'error';  
        $id         = 0;
        if(!empty($user) && !$key && $notify){            
            $this->sendActivationLink($user->id);
            $message    = config('constants.admin_customer_create.mail.success');
            $status     = 'success';    
            $id         = $user->id;            
        }elseif(!$notify && !empty($user)){
            $id         = $user->id;
            $status     = 'success';            
        }
        return array('status' => $status,'message' => $message,'id' => $id,'details_id' => $user->details_id);
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
    public function edit($id)
    {
        $user = User::leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                    ->select(['users.*',
                          'user_details.customerno',
                          'user_details.customername',
                          'user_details.ardivisionno',
                          'user_details.addressline1',
                          'user_details.addressline2',
                          'user_details.addressline3',                          
                          'user_details.city',
                          'user_details.state',
                          'user_details.zipcode', 
                          'sales_persons.person_number',
                          'user_details.itemwarehousecode',
                          'sales_persons.email as salespersonemail',
                          'sales_persons.name as salespersonname'])->where('users.id',$id)->where('users.is_temp',0)->first();

        if(empty($user))
            return abort('403');

        $roles  = Role::all();
        $menus = CustomerMenu::all();
        $customer_menu_access = CustomerMenuAccess::where('user_id',$id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
        } else{
            $menu_access = [];
        }

        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.users.edit', compact('user','menus','menu_access','searchWords'));
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
        $user = User::find($id);
        $max_file_size = (int) AdminsController::parse_size(ini_get('post_max_size'));
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:users,email,' . $id,
            'password' => 'nullable|min:4|confirmed',
            'profile_picture' => 'sometimes|file|mimes:jpg,jpeg,png|max:'.$max_file_size,
        ]);

        $file = $request->file('profile_picture');
        $path = "";
        if($file){
            if($user->profile_image){
                $image_path =str_replace('/','\\',$user->profile_image);
                if(File::exists(public_path().'\\'.$image_path)){
                    File::delete(public_path().'\\'.$image_path);
                }
            }
            
            $user_name = self::removeUnwantedString($user->name);
            $image_name = $user_name.'_'.date('Ymd_his').'.'. $file->extension();
            $file->move(public_path('images'), $image_name);
            $path = 'images/'.$image_name;
        }
        if($file){
            $user->profile_image = $path;
        }

        $customerAccess = CustomerMenuAccess::where('user_id',$id)->first();
        $access_menus  = "";
        $menus = $request->menus;
        $total_count = count($menus);
        $i = 1;
        foreach($request->menus as $menu){
            if($i == $total_count ){
                $access_menus .= "$menu";
            } else {
                $access_menus .= "$menu,";
            }
            $i++;
        }
        if($customerAccess){
            $customerAccess->access = $access_menus;
            $customerAccess->save();
        } else {
            CustomerMenuAccess::create([
                'user_id' => $id,
                'access' => $access_menus
            ]);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $is_update = NewPasswordController::change_vmi_password($id,$request->password);
            //$user->password = Hash::make($request->password);
            
            if($is_update) {
                $user->password = Hash::make($request->password);
            } else {
                session()->flash('error', config('constants.vmi_password_not_update'));   
                return back();    
            }
            //$user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        $user_detail = UserDetails::where('user_id',$id)->get()->first();
        $itemwarehousecode = $request->input('itemwarehousecode');
        $userDetails = UserDetails::find($user_detail->id);
        $userDetails->itemwarehousecode = $itemwarehousecode;
        $userDetails->save();

        session()->flash('success', config('constants.customer_update.confirmation_message'));
        return back();
    }

    public static function removeUnwantedString($name){
        $change_name = str_replace(' ', '', $name);
        $change_name = str_replace(',', '', $change_name);
        $change_name = str_replace(':', '', $change_name);
        return $change_name;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $user = User::find($id);
        // if (!is_null($user)) {            
        //     $user['is_deleted'] = 1;
        //     $user['active'] = 0;
        //     $user->save();
        // }

        $user_detail = UserDetails::find($id);
        if($user_detail){
            $user_detail->is_active = 1;
            $user_detail->save();
            $customers = UserDetails::where('user_id',$user_detail->user_id)->get();
            $is_delete = true;
            foreach($customers as $customer) {
                if($customer->is_active == 0){
                    $is_delete = false;
                }
            }
            if($is_delete) {
                $user= User::find($user_detail->user_id);
                if($user) {
                    $user->is_deleted = 1;
                    $user->active = 0;
                    $user->save();
                }
            }
        }
        session()->flash('success', config('constants.customer_delete.confirmation_message'));
        return back();
    }

    // get customer information
    public function getCustomerInfo(Request $request){
        $search_text = $request->search_text;
        $filter_data = [];
        $sdeApi = new SDEApi();
        $contact_info = array(); 
        $customer_no = [];
        $res = [];
        $is_duplicate = false;
        if (filter_var($search_text, FILTER_VALIDATE_EMAIL)) {
            $contact_data = [
                "index" => "kEmailAddress",
                "filter" => [
                    [   
                        "column" =>  "EmailAddress",
                        "type" => "equals",
                        "value" => $search_text,
                        "operator" => "and"
                    ],
                ],
            ];
            $contact_information = $sdeApi->Request('post','Contacts',$contact_data);
            // dd($contact_information);
            if(!empty($contact_information) && $contact_information['contacts'] && !empty($contact_information['contacts'])) {
                if(count($contact_information['contacts']) == 1){
                    // $customer_no = $contact_information['contacts'][0]['customerno']; 
                    $contact_info[] = $contact_information['contacts'][0];
                } else {
                    $is_duplicate = true;
                    foreach($contact_information['contacts'] as $contacts) {
                        // $customer_no[] = $contacts['customerno'];
                        $contact_info[] = $contacts;
                    }
                }
            } else {
                // echo json_encode(['success' => false,'message' => 'No Conacts found in this email address']);
                echo json_encode(['success' => false,'message' => config('constants.customer_not_found')]);
                die();    
            }
        } else {
            // echo json_encode(['success' => false,'message' => 'Email Address not valid']);
            echo json_encode(['success' => false,'message' => config('constants.error_email')]);
            die();
        }

        if(!$is_duplicate){         
            $filter_data =  [
                "column"=>"customerno",
                "type"=>"equals",
                "value"=>$contact_info[0]['customerno'],
                "operator"=>"and"
            ];
            $data = array(            
                "filter" => [
                    $filter_data
                ],
                "offset" => 1,
                "limit" => 100,
            );
            $response = $sdeApi->Request('post','Customers',$data);
            if(!empty($response) && isset($response['customers']) && !empty($response['customers'])){
                $res['customers'][0] = $response['customers'][0];
                $res['customers'][0]['contact_info'] = $contact_info[0];
            } else {
                echo json_encode(['success' => false,'message' => 'No customers found on this email']);
                die();
            }
        } else {
            foreach($contact_info as $k => $cno) {
                $filter_data =  [
                    "column"=>"customerno",
                    "type"=>"equals",
                    "value"=>$cno['customerno'],
                    "operator"=>"and"
                ];
                $req_data = array(            
                    "filter" => [
                        $filter_data
                    ],
                    "offset" => 1,
                    "limit" => 100,
                );
                $response = $sdeApi->Request('post','Customers',$req_data);
                if(!empty($response) && isset($response['customers']) && !empty($response['customers'])){
                    $res['customers'][$k] = $response['customers'][0];
                    $res['customers'][$k]['contact_info'] = $cno;
                }
            }
        } 
        $customer = Customer::leftjoin('user_details','users.id','=','user_details.user_id')
                    ->where('users.email',$search_text)
                    ->orWhere('user_details.email',$search_text)
                    ->orWhere('user_details.customerno',$search_text)->first();
                
        $res['user'] = $customer;
        $res['success'] = true;
        // $res['contact_info'] = $contact_info;
        // dd($res);
        echo json_encode($res);
        die();
    }

    public function UserAccessRequest(request $request){
     
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
        
        if($this->superAdmin){
            if($search) {
                $users1 = SignupRequest::leftjoin('users','signup_requests.email','=','users.email');

                if($order){
                    $order_column = "signup_requests.$order";
                    $users1->orderBy($order_column,$order_type);
                }
                            
                $users = $users1->where('signup_requests.status',0)
                        ->where(function($users) use($search){
                            $users->orWhere('signup_requests.full_name','like','%'.$search.'%') 
                            ->orWhere('signup_requests.company_name','like','%'.$search.'%') 
                            ->orWhere('signup_requests.email','like','%'.$search.'%') 
                            ->orWhere('signup_requests.phone_no','like','%'.$search.'%');
                        })
                        ->select(['signup_requests.*','users.id as user_id'])
                        ->offset($offset)
                        ->limit(intval($limit))
                        ->get();

                $userss = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->orderBy('signup_requests.id','DESC')
                            ->where('signup_requests.status',0)
                            ->where(function($users) use($search){
                                $users->orWhere('signup_requests.full_name','like','%'.$search.'%') 
                                ->orWhere('signup_requests.company_name','like','%'.$search.'%') 
                                ->orWhere('signup_requests.email','like','%'.$search.'%') 
                                ->orWhere('signup_requests.phone_no','like','%'.$search.'%');
                            })
                            ->paginate(intval($limit));
            } else {
                $users1 = SignupRequest::leftjoin('users','signup_requests.email','=','users.email');

                if($order){
                    $order_column = "signup_requests.$order";
                    $users1->orderBy($order_column,$order_type);
                }
                
                $users = $users1->where('signup_requests.status',0)
                            ->offset($offset)
                            ->limit(intval($limit))
                            ->select(['signup_requests.*','users.id as user_id'])
                            ->get();  
                $userss = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->orderBy('signup_requests.id','DESC')
                            ->where('signup_requests.status',0)
                            ->paginate(intval($limit));
            } 
            $print_users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email');
            if($order){
                $order_column = "signup_requests.$order";
                $print_users->orderBy($order_column,$order_type);
            }
            $print_users->where('signup_requests.status',0)
                            ->select(['signup_requests.*','users.id as user_id'])
                            ->get();  
        } else {
            $manager = $this->user;
            if($search) {
                $users1 = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->leftjoin('user_details','user_details.user_id','=','users.id')
                            ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                            ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                            ->leftjoin('admins','sales_persons.email','=','admins.email');
                if($order){
                    $order_column = "signup_requests.$order";
                    $users1->orderBy($order_column,$order_type);
                }
                            $users1->where('admins.id',$manager->id)
                            ->where('signup_requests.status',0)
                            ->where(function($users1) use($search){
                                $users1->orWhere('signup_requests.full_name','like','%'.$search.'%') 
                                ->orWhere('signup_requests.company_name','like','%'.$search.'%') 
                                ->orWhere('signup_requests.email','like','%'.$search.'%') 
                                ->orWhere('signup_requests.phone_no','like','%'.$search.'%');
                            })
                            ->offset($offset)
                            ->limit(intval($limit))
                            ->select(['signup_requests.*','users.id as user_id'])
                            ->get();  
                $userss = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->leftjoin('user_details','user_details.user_id','=','users.id')
                            ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                            ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                            ->leftjoin('admins','sales_persons.email','=','admins.email')
                            ->orderBy('signup_requests.id','DESC')
                            ->where('admins.id',$manager->id)
                            ->where('signup_requests.status',0)
                            ->where(function($users) use($search){
                                $users->orWhere('signup_requests.full_name','like','%'.$search.'%') 
                                ->orWhere('signup_requests.company_name','like','%'.$search.'%') 
                                ->orWhere('signup_requests.email','like','%'.$search.'%') 
                                ->orWhere('signup_requests.phone_no','like','%'.$search.'%');
                            })
                            ->paginate(intval($limit));
            } else {
                $users1 = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->leftjoin('user_details','user_details.user_id','=','users.id')
                            ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                            ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                            ->leftjoin('admins','sales_persons.email','=','admins.email')
                            ->where('admins.id',$manager->id);
                            if($order){
                                $order_column = "signup_requests.$order";
                                $users1->orderBy($order_column,$order_type);
                            }
                            $users = $users1->where('signup_requests.status',0)
                            ->offset($offset)
                            ->limit(intval($limit))
                            ->select(['signup_requests.*','users.id as user_id'])
                            ->get();  
                $userss = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->leftjoin('user_details','user_details.user_id','=','users.id')
                            ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                            ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                            ->leftjoin('admins','sales_persons.email','=','admins.email')
                            ->where('admins.id',$manager->id)
                            ->orderBy('signup_requests.id','DESC')
                            ->where('signup_requests.status',0)
                           ->paginate(intval($limit));  
            }
            $print_users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                        ->leftjoin('user_details','user_details.user_id','=','users.id')
                        ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                        ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                        ->leftjoin('admins','sales_persons.email','=','admins.email')
                        ->where('admins.id',$manager->id);
                        if($order){
                            $order_column = "signup_requests.$order";
                            $print_users->orderBy($order_column,$order_type);
                        }
                        $print_users->where('signup_requests.status',0)
                        ->select(['signup_requests.*','users.id as user_id'])
                        ->get();  
        }
        $paginate = $userss->toArray();
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        $paginate['links'] = UsersController::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        return view('backend.pages.users.signup-request',compact('users','search','paginate','limit','print_users','order','order_type','searchWords')); 
    }

    public function getUserRequest($user_id,$admin_token = ''){
        $is_notification = Notification::where('to_user',0)->where('action','like','%'.URL::full().'%')->where('status',1)->where('is_read',0)->first();
        if($is_notification){
            $is_notification->status = 0;
            $is_notification->is_read = 1;
            $is_notification->save();
        }

        $current_user = $this->user;
        
        $isManager    = DashboardController::isManager($user_id,$current_user);

        if($this->superAdmin || $isManager)
            $admin      = $current_user;
        else
            $admin      = Admin::where('unique_token', $admin_token)->first();

            
        $request_id = isset($_GET['request']) ? $_GET['request'] : 0;
        $customers  = array();
        if(!empty($admin)){
            if(is_numeric($user_id)){
                $user = User::find($user_id);              
                $email_address = $user->email;            
            }else{
                $email_address  = $user_id;
                $user           = array();
            }
            $userinfo           = array();
            if($request_id)
                $userinfo       = SignupRequest::where('id',$request_id)->first();
            else
                $userinfo       = SignupRequest::where('email',$email_address)->first();
                
            $is_error = false;
            $is_error_message = "";
            if(filter_var($email_address, FILTER_VALIDATE_EMAIL)) {               
                $data = array(            
                    "index" => "kEmailAddress",
                    "filter" => [
                        [   
                            "column" =>  "EmailAddress",
                            "type" => "equals",
                            "value" => $email_address,
                            "operator" => "and"
                        ],
                    ],
                    "offset" => 1,
                    "limit" => 100,
                );
                $sdeApi = new SDEApi();         
                // $res = $sdeApi->Request('post','Customers',$data);
                $res = $sdeApi->Request('post','Contacts',$data);
                $contact_info =  array();
                /* contact getting work start */
                if(!empty($res) && isset($res['contacts']) && !empty($res['contacts'])){
                    if(count($res['contacts']) == 1){
                        // $contact_info[] = $res['contacts'][0];
                        $data1 = array(            
                            "filter" => [
                                [
                                    "column"=>"customerno",
                                    "type"=>"equals",
                                    "value"=>$res['contacts'][0]['customerno'],
                                    "operator"=>"and"
                                ],
                            ],
                        );
                        $res1 = $sdeApi->Request('post','Customers',$data1);
                        if(!empty($res1['customers'])){                   
                            $customers[0] = $res1['customers'][0];
                            $customers[0]['contact_info'] = $res['contacts'][0];
                        } //else return  'No customer found this Email';
                    } else {
                        foreach($res['contacts'] as $ky => $con) {
                            // $contact_info[] = $con;
                            $data1 = array(            
                                "filter" => [
                                    [
                                        "column"=>"customerno",
                                        "type"=>"equals",
                                        "value"=>$con['customerno'],
                                        "operator"=>"and"
                                    ],
                                ],
                            );
                            $res1 = $sdeApi->Request('post','Customers',$data1);
                            if(!empty($res1['customers'])){                   
                                $customers[$ky] = $res1['customers'][0];
                                $customers[$ky]['contact_info'] = $con;
                            }
                        }
                    }
                } else {
                    $is_error = true;
                    $is_error_message = config('constants.admin.customer.no_conatct_found');
                    // return 'NO user contact emails found';
                }
                /* contact getting work end */
                Auth::guard('admin')->login($admin);
                $searchWords = SearchWord::where('type',1)->get()->toArray();
                // dd($customers);
                return view('backend.pages.users.user_request',compact('customers','user','userinfo','searchWords','is_error','is_error_message')); 
            } //else return back()
        }
       return abort('403');
     
    }

    public function sendActivationLink($user_id = 0){
        if($user_id){
            $user = User::find($user_id);
            $res = [];
            if($user) {
                $user->active = 1;
                $user->activation_token = '';
                $user->save();
                
                $token = Str::random(30);
                $_token = Hash::make($token);
                $is_password_reset = DB::table('password_resets')->where('email',$user->email)->first();
                if($is_password_reset){
                    DB::table('password_resets')->where('email',$user->email)->update(['token' => $_token,'created_at' =>date('Y-m-d h:i:s')]);
                } else {
                    DB::table('password_resets')->insert(
                        ['email' => $user->email, 'token' => $_token, 'created_at' => date('Y-m-d h:i:s')]
                    );
                }

                // signup request remove
                $signup_request = SignupRequest::where('email',$user->email)->where('status',0)->first();
                if($signup_request){
                    $signup_request->status = 1;
                    $signup_request->save();
                }

                // notification remove
                $notification = Notification::where('type','Sign Up')->where('from_user',$user->id)->where('status',1)->where('is_read',0)->first();
                if($notification){
                    $notification->status = 0; 
                    $notification->is_read = 1;
                    $notification->save(); 
                }

                $url = config('app.url').'reset-password/'.$token.'?email='.$user->email;
                $details['mail_view']       =  'emails.email-body';
                $details['link']            =  $url;
                $details['namealias'] = 'Hi '.$user->name;
                $details['title']           = config('constants.email.admin.customer_activate.title');   
                $details['subject']         = config('constants.email.admin.customer_activate.subject');
                $body      = config('constants.email.admin.customer_activate.body');
                $details['body'] = $body;
                $customer_emails = config('app.test_customer_email');
                $is_local = config('app.env') == 'local' ? true : false;
                if($is_local){
                    self::commonEmailSend($customer_emails,$details);
                    // Mail::bcc(explode(',',$customer_emails))->send(new \App\Mail\SendMail($details));
                } else {
                    try {
                        Mail::to($user->email)->send(new \App\Mail\SendMail($details));
                    } catch (\Exception $e) {
                        Log::error('An error occurred while sending the mail: ' . $e->getMessage());
                    } 
                }

                $res = ['success' => true, 'message' =>config('constants.customer_activate.confirmation_message')];

                $SchedulerLog = new SchedulerLogController();
                $SchedulerLog->CreateScheduler($user->id);
            
            } else {
                $res = ['success' => false, 'message' => config('constants.customer_not_found')];
            }
            return json_encode($res);

        }

    }
    public function getUserActive(Request $request){
        $user_id = $request->user_id;
        return $this->sendActivationLink($user_id);
    }

    public function getUserCancel(Request $request){
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $res =[];
        if($user) {
            $user->active = 0;
            $user->activation_token = '';
            $user->is_deleted = 1;
            $user->save();
            $user->delete();
            $res = ['success' => true, 'message' => config('constants.customer_cancel.confirmation_message')];
        } else {
            $res = ['success' => false, 'message' => config('constants.customer_cancel.confirmation_error')];
        }
        echo json_encode($res);
    }

    public function  CustomerInventory($userId) {
        $company_code = '';
        $user_detail_id = ''; 
        $itemwarehousecode = '';
        $user_details = UserDetails::where('user_id',$userId)->first();
        if($user_details){
            $company_code       = $user_details->vmi_companycode;
            $user_detail_id     = $user_details->id;
            $itemwarehousecode  = $user_details->itemwarehousecode;
        }
        $constants = config('constants');
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.orders.vmi-inventory-list',compact('company_code','user_detail_id','constants','searchWords','itemwarehousecode')); 
    }

    public function CustomerChangeOrders(Request $request){
        $user   = $this->user;
        $limit = $request->input('limit');
        $order = $request->input('srorder');
        $order_type = $request->input('ortype');
        if($order){
            if($order == 'customerno'){
                $order_column = "user_details.$order";
            }
            if($order == 'name' || $order == 'email' ){
                $order_column = "users.$order";
            }
            if($order == 'ordered_date') {
                $order_column = "change_order_requests.$order";
            }
            if($order == 'manager'){
                $order_column = "sales_persons.name";
            }
        }
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
        $paginate = [];
        if($this->superAdmin){
            if($search){
                $change_request1 = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                    ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                    ->where('request_status',0)
                                                    ->where(function($change_request) use($search){
                                                        $change_request->orWhere('user_details.customerno','like','%'.$search.'%') 
                                                        ->orWhere('sales_persons.name','like','%'.$search.'%') 
                                                        ->orWhere('user_details.customerno','like','%'.$search.'%') 
                                                        ->orWhere('users.email','like','%'.$search.'%');
                                                    });
                if($order){
                    $change_request1->orderBy($order_column,$order_type);
                }

                $change_request = $change_request1->offset($offset)
                                                    ->limit($limit)                                                
                                                    ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);

                $change_requests = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                    ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                    ->where('request_status',0)
                                                    ->where(function($change_request) use($search){
                                                        $change_request->orWhere('user_details.customerno','like','%'.$search.'%') 
                                                        ->orWhere('sales_persons.name','like','%'.$search.'%') 
                                                        ->orWhere('user_details.customerno','like','%'.$search.'%') 
                                                        ->orWhere('users.email','like','%'.$search.'%');
                                                    })
                                                    ->orderBy('change_order_requests.id','DESC')                                                
                                                    ->paginate(intval($limit));
                $paginate = $change_requests->toArray();
            } else {
                $change_request1 = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                    ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                    ->where('request_status',0);
                if($order){
                    $change_request1->orderBy($order_column,$order_type);
                }
                $change_request = $change_request1->offset($offset)
                                                    ->limit($limit)                                                
                                                    ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
                $change_requests = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                    ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                    ->where('request_status',0)
                                                    ->orderBy('change_order_requests.id','DESC')                                                
                                                    ->paginate(intval($limit));
                $paginate = $change_requests->toArray();
            }
        $print_requests1 = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                        ->leftjoin('user_details','users.id','=','user_details.user_id')
                        ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                        ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                        ->where('request_status',0);
        if($order){
            $print_requests1->orderBy($order_column,$order_type);
        }
        $print_requests = $print_requests1->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
        } else {
            $manager = $this->user;
            if($search) {
                $change_request1 = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                                    ->leftjoin('users', 'users.id','=','user_details.user_id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('request_status','=',0)
                                    ->where('admins.id',$manager->id)
                                    ->where(function($change_request) use($search){
                                        $change_request->orWhere('user_details.customerno','like','%'.$search.'%') 
                                        ->orWhere('sales_persons.name','like','%'.$search.'%') 
                                        ->orWhere('user_details.customerno','like','%'.$search.'%') 
                                        ->orWhere('users.email','like','%'.$search.'%');
                                    });
                if($order){
                    $change_request1->orderBy($order_column,$order_type);
                }

                $change_request =  $change_request1->offset($offset)
                                    ->limit($limit)
                                    ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);

                $change_requests = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                                    ->leftjoin('users', 'users.id','=','user_details.user_id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('request_status','=',0)
                                    ->where('admins.id',$manager->id)
                                    ->where(function($change_request) use($search){
                                        $change_request->orWhere('user_details.customerno','like','%'.$search.'%') 
                                        ->orWhere('sales_persons.name','like','%'.$search.'%') 
                                        ->orWhere('user_details.customerno','like','%'.$search.'%') 
                                        ->orWhere('users.email','like','%'.$search.'%');
                                    })
                                    ->orderBy('change_order_requests.id','DESC')
                                    ->paginate(intval($limit));
                $paginate = $change_requests->toArray();

            } else {
                $change_request1 = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                                    ->leftjoin('users', 'users.id','=','user_details.user_id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('request_status','=',0)
                                    ->where('admins.id',$manager->id);
                if($order){
                    $change_request1->orderBy($order_column,$order_type);
                }

                $change_request =  $change_request1->offset($offset)
                                    ->limit($limit)
                                    ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);

                $change_requests = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                                    ->leftjoin('users', 'users.id','=','user_details.user_id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('request_status','=',0)
                                    ->where('admins.id',$manager->id)
                                    ->orderBy('change_order_requests.id','DESC')
                                    ->paginate(intval($limit));
                $paginate = $change_requests->toArray();
            }
            $print_requests1 = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                            ->leftjoin('users', 'users.id','=','user_details.user_id')
                            ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                            ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                            ->leftjoin('admins','sales_persons.email','=','admins.email')
                            ->where('request_status','=',0)
                            ->where('admins.id',$manager->id);
            if($order){
                $print_requests1->orderBy($order_column,$order_type);
            }

            $print_requests = $print_requests1->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);

        }
        $paginate['links'] = self::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.orders.index',compact('change_request','user','paginate','limit','search','print_requests','searchWords','order','order_type'));
    }

    public function CustomerChangeOrderDetails($change_id){
        $request_url = Request()->url();
        $is_notification = Notification::where('to_user',0)->where('action',$request_url)->where('status',1)->where('is_read',0)->first();
        if($is_notification){
            $is_notification->status = 0;
            $is_notification->is_read = 1;
            $is_notification->save();
        }

        $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                ->where('change_order_requests.id',$change_id)
                                                ->orderBy('change_order_requests.id','DESC')
                                                ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email'])->first();

        $data = array(            
            "index" =>"KSDEDESCENDING",
            "filter" => [
                [
                    "column" =>  "salesorderno",
                    "type" =>  "equals",
                    "value" => $change_request->order_no,
                    "operator" =>  "and"
                ],
                [
                    "column" =>  "customerno",
                    "type" =>  "equals",
                    "value" => $change_request->customerno,
                    "operator" =>  "and"
                ],
                [
                    "column" =>  "ardivisionno",
                    "type" =>  "equals",
                    "value" => $change_request->ardivisionno,
                    "operator" =>  "and"
                ],
            ],
        );

        $sdeApi = new SDEApi();         
        $order_detail = $sdeApi->Request('post','SalesOrders',$data);

        if(!empty($order_detail['salesorders'])){
            $order_detail = $order_detail['salesorders'][0];
        } else {
            $order_detail = [];
        }       

        $changed_items = ChangeOrderItem::where('order_table_id',$change_id)->get();
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.orders.change_request',compact('order_detail','changed_items','change_id','change_request','searchWords'));
    }

    public function ExportAllCustomers(){        
        $filename = "customers.csv";
        $data = self::customerExportData();
        $customers = $data['data'];
        $header_array = $data['header'];
        $array_keys = $data['keys'];
        return CustomerExportController::ExportExcelFunction($customers,$header_array,$filename,1,$array_keys);
    }

    public function ExportAllCustomerInPdf(){
        $filename = "customers.pdf";
        $data = self::customerExportData();
        $customers = $data['data'];
        $header_array = $data['header'];
        $array_keys = $data['keys'];
        PdfController::generatePdf($customers,$filename,$header_array,$array_keys);
    }


    //export customer data
    public static function customerExportData(){
        $customers = User::select('user_details.contactname','user_details.contactcode','users.email as contact_email','user_details.email','user_details.customerno','user_details.customername','user_details.ardivisionno','sales_persons.name')->leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                    ->where('users.is_temp',0)->get()->toArray();
        $header_array = array(
            'CONTACT CODE',
            'CONTACT NAME',
            'CONTACT EMAIL',
            'CUSTOMER NUMBER',
            'BENCHMARK REGIONAL MANAGER',
        );
        $array_keys = array(
            'contactcode',
            'contactname',
            'contact_email',
            'customerno',
            'name'
        );
        return ['data' => $customers ,'header' => $header_array, 'keys' => $array_keys ];
    }

    // managers to export
    public function ExportAllManagersToExcel(){
        $data = $this->ExportAllManagersData();
        $filename = "managers.csv";
        $managers = $data['data'];
        $array_headers = $data['headers'];
        $array_keys = $data['keys'];
        return CustomerExportController::ExportExcelFunction($managers,$array_headers,$filename,1,$array_keys);
    }

    public function ExportAllManagersToPdf(){
        $data = $this->ExportAllManagersData();
        $managers = $data['data'];
        $array_headers = $data['headers'];
        $array_keys = $data['keys'];
        $name = 'usermanagers.pdf';
        PdfController::generatePdf($managers,$name,$array_headers,$array_keys);
    }

    public function ExportAllManagersData(){
        $managers = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
        ->get(['sales_persons.person_number','sales_persons.name','sales_persons.email'])->toArray();
        $array_headers = array(
            'MANAGER NUMBER',
            'MANAGER NAME',
            'MANAGER EMAIL'
        );
        $array_keys = array(
            'person_number',
            'name',
            'email'
        );
        return[ 'data' => $managers, 'headers' => $array_headers, 'keys' => $array_keys ];
    }

    // orders
    public function ExportAllOrdersToExcel(){
        $data = $this->ExportAllOrdersData();
        $filename = "change-orders.csv";
        $change_requests = $data['data'];
        $header_array = $data['headers'];
        $keys_array = $data['keys'];
        return CustomerExportController::ExportExcelFunction($change_requests,$header_array,$filename,1,$keys_array);
    }

    public function ExportAllOrdersToPdf(){
        $data = $this->ExportAllOrdersData();
        $name = 'change-order-lists.pdf';
        $change_request = $data['data'];
        $array_headers = $data['headers'];
        $array_keys = $data['keys'];
        PdfController::generatePdf($change_request,$name,$array_headers,$array_keys);
    }
    
    public function ExportAllOrdersData(){
        $user   = $this->user;
        if($this->superAdmin){
            $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                ->orderBy('change_order_requests.id','DESC')
                                                ->get(['user_details.customerno','users.name','users.email','change_order_requests.ordered_date','sales_persons.name as manager','change_order_requests.order_no'])->toArray();

        }elseif(DashboardController::isManager($user->id,$user)){
            $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                ->where('sales_persons.id',$user->id)
                                                ->orderBy('change_order_requests.id','DESC')
                                                ->get(['user_details.customerno','users.name','users.email','change_order_requests.ordered_date','sales_persons.name as manager','change_order_requests.order_no'])->toArray();
        }

        $header_array = array(
            'CUSTOMER NUMBER',
            'CUSTOMER NAME',
            'CUSTOMER EMAIL',
            'ORDER DATE',
            'REGION MANAGER',
            'ORDER NUMBER'
        );
        $keys_array = array(
            'customerno',
            'name',
            'email',
            'ordered_date',
            'manager',
            'order_no'
        );

        return ['data' => $change_request,'headers' => $header_array,'keys' => $keys_array];
    }

    // signup requests
    public function ExportAllSignupToExcel(){
        $data = $this->ExportSignupData();
        $filename = "sign-up-requests.csv";
        $users = $data['data'];
        $header_array = $data['headers'];
        $keys_array = $data['keys'];
        return CustomerExportController::ExportExcelFunction($users,$header_array,$filename,1,$keys_array);
    }
    
    public function ExportAllSignupToPdf(){
        $data = $this->ExportSignupData();
        $name = 'sign-up-requests.pdf';
        $users = $data['data'];
        $array_headers = $data['headers'];
        $array_keys = $data['keys'];
        PdfController::generatePdf($users,$name,$array_headers,$array_keys);
    }

    public function ExportSignupData(){
        $users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                    ->orderBy('signup_requests.id','DESC')
                    ->where('signup_requests.status',0)
                    ->select(['signup_requests.full_name','signup_requests.company_name','signup_requests.email','signup_requests.phone_no'])
                    ->get()->toArray();

        $header_array = array(
            'FULL NAME',
            'COMPANY NAME',
            'EMAIL',
            'PHONE NUMBER',
        );
        $keys_array = array(
            'full_name',
            'company_name',
            'email',
            'phone_no'
        );
        return ['data' => $users, 'headers' => $header_array,'keys' => $keys_array];
    }

    public function GetInventoryItem(Request $request){
        $data = $request->all();
        $item_code = $data['icode'];
        $company_code = $data['ccode'];
        $data = array(                             
            "companyCode"   => $company_code,
            "offset"        => 0,
            "limit"         => 2,
            "filter" => [
                [
                    "column"=> "itemcode",
                    "type"=> "equals",
                    "value"=> $item_code,
                    "operator"=> "and"
                ],
            ], 
        );
        $sdeApi = new SDEApi();
        $response = $sdeApi->Request('post','Products',$data);
        $response = $response['products'];
        $res = ['success' => true,'data' => $response, 'error' => []];
        echo json_encode($res);
        die();
    }

    public function UpdateInventoryItem(Request $request){
        $user = $this->user;
        $data = $request->all();
        $company_code = $data['company_code'];
        $item_code = $data['item_code'];
        $user_detail_id = $data['user_id'];
        $old_quantity = $data['old_quantity'];
        $new_quantity = $data['new_quantity'];
        if(!$new_quantity){
            $res = ['success' => false, 'error' => config('constants.admin.inventory_update.validation.new_quanity')];
            echo json_encode($res);
            die();
        }
        $VmiInventoryRequest = VmiInventoryRequest::create([
            'company_code' => $company_code,
            'item_code' => $item_code,
            'user_detail_id' => $user_detail_id,
            'old_qty_hand' => $old_quantity,
            'new_qty_hand'=> $new_quantity,
            'change_user' => $user->id 
        ]);
        $res = ['success' => false, 'error' => config('constants.admin.inventory_update.error')];
        if($VmiInventoryRequest){
            $res = ['success' => true, 'message' => config('constants.admin.inventory_update.success')];
        }

        echo json_encode($res);
        die();
    }

    public function GetUserVmiData(Request $request){
        $data = $request->all();
        $page = $data['page'];
        $limit = $data['count'];
        $warehousecode = $data['warehousecode'];
        $warehousecode = ($warehousecode != '' && strlen($warehousecode) > 1 && $warehousecode != null) ? $warehousecode : '';
        $ignores = intval($data['ignores']);
        $search_val = $data['search_val'];
        if($page == 0){
            $offset = 1;
        } else {
            $offset = $page * $limit + 1;
        }
        $offset = $offset + $ignores;
        $user_detail_id = $data['user_detail_id'];
        $company_code = $data['company_code'];

        $data = array(                             
            "companyCode"   => $company_code,
            "offset"        => $offset,
            "limit"         => $limit,
        );
        $resource = 'Products';
        $_filter = array();
        if($warehousecode){
            $_filter[] = array("column"     =>  "warehouseCode", 
                                "type"      => "equals",
                                "value"     => $warehousecode,
                                "operator"  => "and");
           
            $resource = 'ItemWarehouses';
        }
        if($search_val != '') {
            $_filter[] = array( "column"    => "itemcode",
                                "type"      => "equals",
                                "value"     => $search_val,
                                "operator"  => "and");
        }

        if(!empty($_filter)){
            $data['filter'] = $_filter;
        }
        
        $sdeApi = new SDEApi();
        $response = $sdeApi->Request('post',$resource,$data);

            

        if(empty($response)){
            $table_code = View::make("components.datatabels.vmi-inventory-list-component")
            ->with("vmiProducts", [])
            ->render();
            $res = ['success' => true, 'table_code' => $table_code,'pagination_code' => '', 'count' => 0];
            echo json_encode($res);
            die(); 
            return;
        }
        
        // Remove unwanted products
        $count = 0;
        $item_inventory = array();
        if($resource == 'ItemWarehouses'){
            $item_inventory['products'] = $response['itemwarehouses'];            
        }else{
            $item_inventory['products'] = $response['products'];
        }
        foreach($item_inventory['products'] as $key => $product){
            $string = $product['itemcode'];
            if (substr($string, 0, 1) === '/') {
                $count = $count + 1;
                unset($item_inventory['products'][$key]);
            }
        }
        
       
         

        // Again get a response
        if($count > 0){
            $offset1 = $offset + $limit;
            $data1 = array(                             
                "companyCode"   => $company_code,
                "offset"        => $offset1,
                "limit"         => $count,
            );
            $sdeApi = new SDEApi();
            $response1 = $sdeApi->Request('post',$resource,$data1);

            $item_inventory2 = array();
            if($resource == 'ItemWarehouses'){
                $item_inventory2['products'] = $response1['itemwarehouses'];            
            }else{
                $item_inventory2['products'] = $response1['products'];
            }

            $item_inventory['products'] = array_merge($item_inventory2['products'],$item_inventory['products']);    
        }
        

        
        if(!empty($item_inventory['products'])){
            $_products = $item_inventory['products'];
            $itemCode = array_column($_products,'itemcode');
            if($warehousecode){
                $inventory_updates = VmiInventoryRequest::select('*')
                                    ->whereIn('item_code',$itemCode)
                                    ->where('company_code',$company_code)
                                    ->where('warehousecode',$warehousecode)
                                    ->whereIn('id', function ($query) {
                                        $query->select(DB::raw('MAX(id)'))
                                            ->from('vmi_inventory_requests')
                                            ->groupBy('item_code');
                                    })
                                    ->get()->toArray(); //->pluck('new_qty_hand','item_code','updated_at');
                
            }else{
            $inventory_updates = VmiInventoryRequest::select('*')
                                    ->whereIn('item_code',$itemCode)
                                    ->where('company_code',$company_code)
                                    ->whereIn('id', function ($query) {
                                        $query->select(DB::raw('MAX(id)'))
                                            ->from('vmi_inventory_requests')
                                            ->groupBy('item_code');
                                    })
                                    ->get()->toArray(); //->pluck('new_qty_hand','item_code','updated_at')
            }                        
            $itemcodes = array();   
            
           

                    
            if(!empty($inventory_updates)){    
                foreach($inventory_updates as $inventory){
                    $key = $inventory['item_code'];
                    $itemcodes[$key] = $inventory;
                }   

                
                foreach($_products as $key => $_product){
                   $itemcode = $_product['itemcode'];
                   $last_updated = isset($_product['lastphysicalcountdate']) ? $_product['lastphysicalcountdate'] : '';
                   if(isset($itemcodes[$itemcode]) && $last_updated != '' && $warehousecode != ''){
                        $stored_date = $itemcodes[$itemcode]['updated_at'];
                        if(strtotime($last_updated) <= strtotime($stored_date)){
                            $item_inventory['products'][$key]['quantityonhand'] = $itemcodes[$itemcode]['new_qty_hand'];
                        }
                   }elseif(isset($itemcodes[$itemcode]) && $warehousecode == ''){
                        $item_inventory['products'][$key]['quantityonhand'] = $itemcodes[$itemcode]['new_qty_hand'];
                   }
                }
            }
        }
        // total 
        $response['meta']['records'] = $response['meta']['records'] - $count;
        $response['meta']['records'] = $response['meta']['records'] - $ignores;
        
        // offset
        $response['meta']['offset'] = $response['meta']['offset'] - $ignores;
        $response['meta']['offset'] = $response['meta']['offset'] - $ignores;
        
        $response['products'] = array_values($item_inventory['products']);

        $table_code = View::make("components.datatabels.vmi-inventory-list-component")
        ->with("vmiProducts", $response['products'])
        ->render();

        $path = '/getAdminVmiData';
        $custom_pagination = MenuController::CreatePaginationData($response,$limit,$page,$offset,$path);

        $pagination_code = View::make("components.admin-vmi-ajax-pagination-component")
        ->with("pagination", $custom_pagination)
        ->render();
        
        $res = ['success' => true, 'table_code' => $table_code,'pagination_code' => $pagination_code, 'count' => $count];
        echo json_encode($res);
        die(); 
    }

    public function SaveUserVmiData(Request $request){
        $user = $this->user;

        
        $data = $request->all();
        $value_changes = json_decode($data['vmi_changes'],true);
        $company_code = $data['company_code'];
        $user_detail_id = $data['user_detail_id'];
        $warehousecode = isset($data['warehousecode']) ? $data['warehousecode'] : '';
        

        // $bodycontent = '<table>
        //                     <thead>
        //                         <tr>
        //                             <th>Customer<br/>Item Number</th>
        //                             <th>Benchmark<br/>Item Number</th>
        //                             <th>Item<br/>Description</th>
        //                             <th>Qty<br/> on Hand</th>
        //                             <th>Quantity<br/>Counted</th>
        //                         </tr>
        //                     </thead>
        //                     <tbody>';
        $data_array_collection = array();
        foreach($value_changes as $key => $value_change){
            $data_array = array();

            $itemcode       = isset($value_change['itemcode']) ? str_replace('#','',$value_change['itemcode']) : 'N/A';
            $description    = isset($value_change['description']) ? $value_change['description'] : 'N/A';
            $old_qty        = isset($value_change['old_qty']) ? $value_change['old_qty'] : 'N/A';
            $new_qty        = isset($value_change['new_qty']) ? $value_change['new_qty'] : 0;


            $data_array['item_key']     = $key;
            $data_array['itemcode']     = $itemcode;
            $data_array['description']  = $description;
            $data_array['old_qty']      = $old_qty;
            $data_array['new_qty']      = $new_qty;
            $data_array_collection[]    = $data_array;

            $item_data = array('company_code'    => $company_code,
                                'item_code'      => $key,
                                'user_detail_id' => $user_detail_id,
                                'old_qty_hand'   => $value_change['old_qty'],
                                'new_qty_hand'   => $value_change['new_qty'],
                                'change_user'    => $user->id,
                                'warehousecode'  => $warehousecode 
                            );

            $VmiInventoryRequest = VmiInventoryRequest::create($item_data);

          

            $bodycontent = '<tr>
                            <td><strong>'.$key.'</strong></td>
                            <td>'.$itemcode.'</td>
                            <td>'.$description.'</td>
                            <td>'.$old_qty.'</td>
                            <td>'.$new_qty.'</td>
                            </tr>';

            $data1 = array(                             
                "companyCode"   => $company_code,
                "method" =>  "post",
                "warehouseCode" => $warehousecode, // ??
                "itemcode" => $key,
                "quantityCounted" => $value_change['new_qty']
            );
            $sdeApi = new SDEApi();
            $response1 = $sdeApi->Request('post','PhysicalCounts',$data1);
            // dd
        }
        // $bodycontent .= '</tbody></table>';
        $details['body_header']           = "<p>Staff User: {$user->name}({$user->email})</p><p>Company Code:{$company_code}</p>
        <br/><p>Please note that Staff User {$user->name} has submitted a request to update the inventory post count for the specified items of VMI company {$company_code}.";   
        $details['subject']               = config('constants.vmi_inventory.subject');
        $body      = config('constants.vmi_inventory.body');
        $details['data_array'] = $data_array_collection;
        $support_emails =  config('app.support_email');
        $details['mail_view']    = "emails.inventory-update";
        $is_local = config('app.env') == 'local' ? true : false;
        //echo $support_emails;
       
        try {
            Mail::to($support_emails)->send(new \App\Mail\SendMail($details));
        } catch (\Exception $e) {                            
            //dd($e->getMessage());
            Log::error('An error occurred while sending the mail: ' . $e->getMessage());
        } 
       
        
        $res = ['success' => true,'message' => config('constants.admin.inventory_update.success')];
        echo json_encode($res);
        die();
    }

    public function ExportVmiInventory(Request $request){
        $data = $request->all();
        $company_code = $data['company_code'];
        $data = array(                             
            "companyCode"   => $company_code
        );
        $sdeApi = new SDEApi();
        $response = $sdeApi->Request('post','Products',$data);

        // export csv file
        $products = $response['products'];
        $filename = "vmi_inventory.csv";
        $header_array = array(
            	'ITEM CODE',
            	'ITEM CODE DESCRIPTION',
            	'VENDOR NAME',
            	'QUANTITY ON HAND',
            	'QUANTITY PURCHASED',
        );
        $keys_array = array(
            'itemcode',
            'itemcodedesc',
            'vendorname',
            'quantityonhand',
            'quantitypurchased'
        );

        if(!empty($products)){
            $itemCode = array_column($products,'itemcode');
            $inventory_updates = VmiInventoryRequest::select('*')
                                    ->whereIn('item_code',$itemCode)
                                    ->where('company_code',$company_code)
                                    ->whereIn('id', function ($query) {
                                        $query->select(DB::raw('MAX(id)'))
                                            ->from('vmi_inventory_requests')
                                            ->groupBy('item_code');
                                    })
                                    ->get()->pluck('new_qty_hand','item_code');

            if(!empty($inventory_updates)){              
                foreach($products as $key => $_product){
                    $itemcode = $_product['itemcode'];
                    if(isset($inventory_updates[$itemcode])){
                        $products[$key]['quantityonhand'] = $inventory_updates[$itemcode];
                    }
                }
            }                        
        }

        return CustomerExportController::ExportExcelFunction($products,$header_array,$filename,1,$keys_array);
    }

    // customer exports
    public function customerExports(Request $request){
        $user = $this->user;
        $limit = $request->input('limit');
        $order = $request->input('srorder');
        $order_type = $request->input('ortype');
        
        if(!$limit){
            $limit = 10;
        }
        
        if($order){
            $order_column = "analaysis_export_requests.$order";
        }

        $offset     = isset($_GET['page']) ? $_GET['page'] : 0;
        if($offset > 1){
            $offset = ($offset - 1) * $limit;
        }
        if(isset($_GET['page']) && $_GET['page'] == 1){
            $offset = $offset - 1;
        }
        $search = $request->input('search');                  
        $paginate = [];
        if($this->superAdmin) { 
            if($search){
                $customer_export_count1 =  AnalaysisExportRequest::where('status',0)
                                            ->where(function($customer_export_count) use($search){
                                                $customer_export_count->orWhere('customer_no','like','%'.$search.'%')
                                                                        ->orWhere('resource','like','%'.$search.'%');
                                            });
                if($order){
                    $customer_export_count1->orderBy($order_column, $order_type);
                }
                
                $customer_export_count = $customer_export_count1->select('analaysis_export_requests.*')->get()->toArray();      
                
                $customer_export_counts = AnalaysisExportRequest::where('status',0)
                                            ->where(function($customer_export_counts) use($search){
                                                $customer_export_counts->orWhere('customer_no','like','%'.$search.'%')
                                                                        ->orWhere('resource','like','%'.$search.'%');
                                            })
                                            ->select('analaysis_export_requests.*')->paginate(intval($limit));
            } else {
                $customer_export_count1 =  AnalaysisExportRequest::where('status',0);
                if($order){
                    $customer_export_count1->orderBy($order_column, $order_type);
                }   
                $customer_export_count = $customer_export_count1->select('analaysis_export_requests.*')->get()->toArray();      
                $customer_export_counts = AnalaysisExportRequest::where('status',0)->select('analaysis_export_requests.*')->paginate(intval($limit));
            }
        } else {
            if($search){
                $customer_export_count1 =  AnalaysisExportRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                ->leftjoin('admins','sales_persons.email','=','admins.email')
                ->where('status',0)->select('analaysis_export_requests.*')
                ->where(function($customer_export_count) use($search){
                    $customer_export_count->orWhere('analaysis_export_requests.customer_no','like','%'.$search.'%')
                                            ->orWhere('analaysis_export_requests.resource','like','%'.$search.'%');
                });

                if($order){
                    $customer_export_count1->orderBy($order_column, $order_type);
                }

                $customer_export_count = $customer_export_count1->get()->toArray();

                $customer_export_counts = AnalaysisExportRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                ->leftjoin('admins','sales_persons.email','=','admins.email')
                ->where('status',0)
                ->where(function($customer_export_counts) use($search){
                    $customer_export_counts->orWhere('analaysis_export_requests.customer_no','like','%'.$search.'%')
                                            ->orWhere('analaysis_export_requests.resource','like','%'.$search.'%');
                })
                ->select('analaysis_export_requests.*')->paginate(intval($limit));
            } else {
                $customer_export_count1 =  AnalaysisExportRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                ->leftjoin('admins','sales_persons.email','=','admins.email')
                ->where('status',0);
                if($order){
                    $customer_export_count1->orderBy($order_column, $order_type);
                }
                $customer_export_count = $customer_export_count1->select('analaysis_export_requests.*')->get()->toArray();
                
                $customer_export_counts = AnalaysisExportRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                ->leftjoin('admins','sales_persons.email','=','admins.email')
                ->where('status',0)
                ->select('analaysis_export_requests.*')->paginate(intval($limit));
            }
        }

        $paginate = $customer_export_counts->toArray();
        $paginate['links'] = self::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);

        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.exports.index',compact('customer_export_count','user','paginate','limit','search','searchWords','order','order_type'));
    }

    // customer export info
    public function customerExportInfo(Request $request,$id){
        $customer_export_count =  AnalaysisExportRequest::where('id',$id)->select('analaysis_export_requests.*')->get()->first();
        $user_detail = UserDetails::where('id',$customer_export_count->user_detail_id)->get()->first();
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.exports.info',compact('customer_export_count','user_detail','searchWords'));
    }

    public function ExportAllExports(){
        $data = self::ExportAllExportData();
        $filename = "export_requests.csv";        
        // $contents = '';
        // $delimiter = ',';
        // $enclosure = '"';
        // $escape = '\\';

        // $header = array(
        //         'CUSTOMER NUMBER',
        //         'RESOURCE',
        //         'TYPE',
        //         'REQUESTED DATE'
        // );
        // $contents .= implode($delimiter, array_map(function($value) use ($enclosure, $escape) {
        //     return $enclosure . str_replace($enclosure, $escape . $enclosure, $value) . $enclosure;
        // }, $header)) . "\n";

        // foreach ($requests as $res) {
        //     $row_array = array();
        //     foreach($requests as $request) {
        //         $type = $request['type'] == 1 ? 'CSV' : 'PDF';
        //         $requested_date = Carbon::parse($request['created_at'])->format('M d,Y');
        //         $row_array =  array(
        //                 $request['customer_no'],
        //                 $request['resource'],
        //                 $type,
        //                 $requested_date
        //         );
        //     }
        //     $row = $row_array;
        //     $contents .= implode($delimiter, array_map(function($value) use ($enclosure, $escape) {
        //         return $enclosure . str_replace($enclosure, "'", $value) . $enclosure;
        //     }, $row)) . "\n";
        // }

        // $headers = array(
        //     'Content-Type' => 'text/csv',
        //     'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        // );
        // $response = response()->stream(function() use ($contents) {
        //     $stream = fopen('php://output', 'w');
        //     fwrite($stream, $contents);
        //     fclose($stream);
        // }, 200, $headers);

        // return $response;
        $users = $data['data'];
        $header_array = $data['headers'];
        $keys_array = $data['keys'];
        return CustomerExportController::ExportExcelFunction($users,$header_array,$filename,1,$keys_array);
    }

    public function ExportAllExportsPdf(){
        $data = self::ExportAllExportData();
        $name = 'Exports-list.pdf';
        $requests = $data['data'];
        $array_headers = $data['headers'];
        $array_keys = $data['keys'];
        PdfController::generatePdf($requests,$name,$array_headers,$array_keys);
    }

    public static function ExportAllExportData(){
        $requests =  AnalaysisExportRequest::leftjoin('user_details','user_details.user_id','=','analaysis_export_requests.user_detail_id')
        ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
        ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
        ->leftjoin('admins','sales_persons.email','=','admins.email')
        ->where('status',0)
        ->select('analaysis_export_requests.customer_no','analaysis_export_requests.resource','analaysis_export_requests.type','analaysis_export_requests.created_at')->get()->toArray();
        $final_array = array();
        foreach($requests as $request){
            $test_array = array(); 
            $type = $request['type'] == 1 ? 'CSV' : 'PDF';
            $requested_date = Carbon::parse($request['created_at'])->format('M d,Y');
            $test_array['customer_no'] = $request['customer_no']; 
            $test_array['resource'] = $request['resource']; 
            $test_array['type'] = $type; 
            $test_array['created_at'] = $requested_date;
            $final_array[] = $test_array;
        }
        $array_headers = array(
            'CUSTOMER NUMBER',
            'RESOURCE',
            'TYPE',
            'REQUESTED DATE'
        );
        $array_keys = array(
            'customer_no',
            'resource',
            'type',
            'created_at'
        );
        return ['data' => $final_array, 'headers' => $array_headers, 'keys' => $array_keys];
    }

    public function removeWelcome(Request $request){
        $request->session()->forget('welcome');
        echo json_encode(['test']);
        die();
    }

    /* TEMP ACCESS FUNCTION INTEGRATION */
    public function CheckTempCustomerAccess($customerNo = ''){    
        $SDEAPi     = new SDEApi();
        $data = array(            
            "filter" => [
                [
                    "column"=>"customerno",
                    "type"=>"equals",
                    // "value"=>$customer[0]['customerno'],
                    "value"=>$customerNo,
                    "operator"=>"and"
                ]
            ],
            "offset" => 1,
            "limit" => 1
        );
        $response   = $SDEAPi->Request('post','Customers',$data);
        $customer   = array();  
        if(!empty($response)){
            $customers = $response['customers'];
            $customer = $customers[0];
            $customer['email']          =  $customer['emailaddress'];
            $customer['contactcode']    = 'temp_'.$customerNo;
            $customer['contactname']    = $customer['customername'];
            $customer['contactemail']   = 'temp_'.$customerNo.'@benchmarkproducts.com';
            $customer['is_temp']        = 1;
            $customer['vmi_password']   = 'temp_'.$customerNo;
            
        }
        $_res = $this->CreateCustomer($customer,0,0);       
        return $_res;
    }

    public function customerLogin(Request $request,$id,$user_detail_id){    
        if(!auth('admin')->check()){
            return redirect()->route('admin.login');  
        }
        $is_temp =  0;
        if(!is_numeric($id)){
            $customerNo = $id;
            $contactemail   = 'temp_'.$id.'@benchmarkproducts.com';
            $_temp = UserDetails::where('customerno',$id)->where('users.email',$contactemail)
                        ->where('users.is_temp',1)
                        ->leftjoin('users','users.id','=','user_details.user_id')
                        ->select('user_details.*')
                        ->first();
            if(!empty($_temp)){
                $id                 = $_temp['user_id'];
                $user_detail_id      = $_temp['id'];
                $is_temp =  1;      
            }else{
                $_temp   = $this->CheckTempCustomerAccess($id);
                $id      = $_temp['id'];
                $user_detail_id = $_temp['details_id'];
                $is_temp =  1;                
            }
            if($is_temp)
                $request->session()->put('temp_access',$customerNo);
        }

        $email = Auth::guard('admin')->user()->email;
        // Auth::guard('admin')->logout();
        if(Auth::guard('web')->loginUsingId($id)){
            self::customerSessions($request,$email,$user_detail_id);
            return redirect()->route('auth.customer.dashboard'); 
        }else{
            //Auth::guard('web')->loginUsingId($temp_id);
            //self::SetTempSessions($request,$email,$id);
            //return redirect()->route('auth.customer.dashboard');
            return abort('403'); 
        }
    }

    private static function SetTempSessions(Request $request,$admin_email = null,$current_user_no = null){
        $user = Auth::user();     
        $data = array(            
                "filter" => [
                    [
                        "column"=>"customerno",
                        "type"=>"equals",
                        // "value"=>$customer[0]['customerno'],
                        "value"=>$current_user_no,
                        "operator"=>"and"
                    ]
                ],
                "offset" => 1,
                "limit" => 1
            );

         
        $SDEAPi     = new SDEApi();
        $response   = $SDEAPi->Request('post','Customers',$data);
        $customers   = null;
        if(!empty($response)){           
            $customers = isset($response['customers']) ? $response['customers'] : null;
            $customer = (count($customers) > 0) ?  $customers[0] : null;
            if(!empty($customer) && isset($customer['vmi_companycode']) && $customer['vmi_companycode'] != ''){
                $vmi_nextonsitedate = $response['customers'][0]['vmi_nextonsitedate'];
                $vmi_physicalcountdate = $response['customers'][0]['vmi_physicalcountdate'];

                if($vmi_nextonsitedate != '')
                    $vmi_nextonsitedate = Carbon::createFromFormat('Y-m-d',$vmi_nextonsitedate)->format('d-m-Y');
                
                if($vmi_physicalcountdate != '')
                    $vmi_physicalcountdate = Carbon::createFromFormat('Y-m-d',$vmi_physicalcountdate)->format('d-m-Y');

                $request->session()->put('vmi_nextonsitedate',$vmi_nextonsitedate);
                $request->session()->put('vmi_physicalcountdate',$vmi_physicalcountdate); 
                
               // $request->session()->put('vmi_nextonsitedate',Carbon::createFromFormat('Y-m-d',$customer['vmi_nextonsitedate'])->format('d-m-Y'));            
               // $request->session()->put('vmi_physicalcountdate',Carbon::createFromFormat('Y-m-d', $customer['vmi_physicalcountdate'])->format('d-m-Y'));            
            }
        }
       
        $request->session()->put('customers',$customers);        
        $selected_customer = array();
        foreach($customers as $cs) {
            if($cs['customerno'] == $current_user_no){
                $selected_customer = $cs;
            }
        }
        $request->session()->put('selected_customer',$selected_customer);
        
        $request->session()->put('customer_no',$current_user_no);
        
        if($admin_email) {
            $request->session()->put('by_admin',$admin_email);
        }
        return true;
    }


    public function adminLogin(Request $request){
        $by_admin = $request->session()->get('by_admin');
        if(!$by_admin){
            return redirect()->route('login');   
        }
        $admin = Admin::where('email',$by_admin)->get("id")->first();
        Auth::guard('web')->logout();
        Session()->flush();
        if(Auth::guard('admin')->loginUsingId($admin->id)) {
            return redirect()->route('admin.dashboard');  
        }
    }

    


    private static function customerSessions(Request $request,$admin_email = null,$user_detail_id = 0){
        $user = Auth::user();
        $customer = UserDetails::where('user_id',$user->id)
                    ->leftjoin('users','users.id','=','user_details.user_id')
                    ->select('user_details.*','users.profile_image')
                    ->get();
        $current_user_no = UserDetails::where('id',$user_detail_id)->pluck('customerno')->first();
        $current_user_no = $current_user_no ? $current_user_no : $customer[0]['customerno'];
        if($user->is_vmi){
            $data = array(            
                "filter" => [
                    [
                        "column"=>"customerno",
                        "type"=>"equals",
                        // "value"=>$customer[0]['customerno'],
                        "value"=>$current_user_no,
                        "operator"=>"and"
                    ]
                ],
                "offset" => 1,
                "limit" => 1
            );

            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','Customers',$data);
            if(!empty($response)){
                $_customers = isset($response['customers']) ? $response['customers'] : null;
                $_customer = (count($_customers) > 0) ?  $_customers[0] : null;
                if(!empty($_customer) && isset($_customer['vmi_companycode']) && $_customer['vmi_companycode'] != ''){    
                    $vmi_nextonsitedate = $response['customers'][0]['vmi_nextonsitedate'];
                    $vmi_physicalcountdate = $response['customers'][0]['vmi_physicalcountdate'];

                    if($vmi_nextonsitedate != '')
                       $vmi_nextonsitedate = Carbon::createFromFormat('Y-m-d',$vmi_nextonsitedate)->format('d-m-Y');
                    
                    if($vmi_physicalcountdate != '')
                        $vmi_physicalcountdate = Carbon::createFromFormat('Y-m-d',$vmi_physicalcountdate)->format('d-m-Y');

                    $request->session()->put('vmi_nextonsitedate',$vmi_nextonsitedate);
                    $request->session()->put('vmi_physicalcountdate',$vmi_physicalcountdate);            
                }
            } 
        }      
        $request->session()->put('customers',$customer);
        
        // Add selected customers
        $selected_customer = array();
        foreach($customer as $cs) {
            if($cs['customerno'] == $current_user_no){
                $selected_customer = $cs;
            }
        }
        $request->session()->put('selected_customer',$selected_customer);
        
        $request->session()->put('customer_no',$current_user_no);
        if($admin_email) {
            $request->session()->put('by_admin',$admin_email);
        }
    }


    public function insertCustomerNumbers(Request $request) {
        $customer_numbers = UserDetails::select('customerno')->groupBy('customerno')->get()->toArray();
        $is_empty = CustomerUnqiue::find(1);
        if(!$is_empty) {
            $is_insert = CustomerUnqiue::insert($customer_numbers);
            dd($is_insert);
        } else {
            dd('Already inserted');
        }


        // get user details:
        // $customers = CustomerUnqiue::with('getUserdetails')->get()->toArray();
        // dd($customers);
        // $search_word = "Abbvi";
        // $search_function = function(query) use ($search_word) {
        //     dd($search_word)
        // }
        // $customers = CustomerUnqiue::with(['getUserdetails' => function ($query) use ($search_word) {
        //     $query->where('email', 'like', "%{$search_word}%");
        // }])->get()->toArray();
        // $request = ['search' => $search_word,'type' => '','manager' => ''];
        // $customers = CustomerUnqiue::whereHas('getUserdetails', function ($query) use ($request) {
        //     if($request['search'] != ""){
        //         $query->where('email', 'like', "%{$request['search']}%");
        //     }
        // })->with('getUserdetails');
        // // ->get();
        // dd($customers->paginate(intval(10))->toArray());
        // $userss = $lblusers->paginate(intval($limit))
        // getUserdetails
        
    }

    public static function commonEmailSend($send_emails,$send_details){        
        if(!is_array($send_emails)) {
            $send_emails = explode(',',$send_emails);
        } 
        try {
            Mail::bcc($send_emails)->send(new \App\Mail\SendMail($send_details));
        } catch (\Exception $e) {
            Log::error('An error occurred while sending the mail: ' . $e->getMessage());
        }   
    }

    public function ManagerCustomersView($id,$is_exits) {
        $search = '';
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        $sales_person_number = "";
        if($is_exits){
            $salesPerson = SalesPersons::where('id',$id)->first();
            $sales_person_number = $salesPerson->person_number; 
        } else {
            $sales_person_number = $id;
        }
        return view('backend.pages.managers.customers',compact('search','searchWords','sales_person_number'));
    }

    public function ManagerCustomers(Request $request){
        $data = $request->all();
        $page = $data['page'];
        $limit = $data['count'];
        if($page == 0){
            $offset = 1;
        } else {
            $offset = $page * $limit + 1;
        }
        $sales_person_number = $data['sales_person_number'];
        $data = array( 
            "index" => "kSalesperson",           
            "filter" => [
                [
                    "column"=> "SalespersonNo",
                    "type"=> "equals",
                    "value"=> $sales_person_number,
                    "operator"=> "and"
                ],
            ],
            "offset" => $offset,
            "limit" => $limit,
        );
        $SDEAPi = new SDEApi();
        $response   = $SDEAPi->Request('post','Customers',$data);
        $manager_customers  = $response['customers'];
        foreach($manager_customers as $key => $customer) {
            $manager_customers[$key]['is_exits'] = false;
           // $is_already_exits = UserDetails::where('customerno',$customer['customerno'])->first();

            $is_already_exits = UserDetails::where('customerno',$customer['customerno'])
                        ->where('users.is_temp',0)
                        ->leftjoin('users','users.id','=','user_details.user_id')
                        ->select('user_details.*','users.profile_image')
                        ->first();

            if($is_already_exits){  
                $manager_customers[$key]['is_exits'] = true;
                $manager_customers[$key]['user_detail'] = $is_already_exits->toArray();
            }
        }
        $path = '/admin/manager/customers';
        $table_code = View::make("components.backend-manager-customers")
        ->with("manager_customers", $manager_customers)
        ->render();
        $pagination_code = "";
        if(count($manager_customers) > intval($limit)){
            $custom_pagination = MenuController::CreatePaginationData($response,$limit,$page,$offset,$path);
            $pagination_code = View::make("components.admin-vmi-ajax-pagination-component")
            ->with("pagination", $custom_pagination)
            ->render();
        }
        $res = ['success' => true, 'table_code' => $table_code,'pagination_code' => $pagination_code];
        echo json_encode($res);
        die(); 
    }


    public function ManagerCreate(){
        $constants = config('constants');
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.managers.create',compact('constants','searchWords'));
    }

    public function ManagerInfo(Request $request){
        $search_text = $request->search_text;
        $data = array(            
            "filter" => [
                [
                    "column"=>"EmailAddress",
                    "type"=>"equals",
                    "value"=>$search_text,
                    "operator"=>"and"
                ]
            ],
            "offset" => 1,
            "limit" => 100,
        );
        $sdeApi = new SDEApi();         
        $res = $sdeApi->Request('post','Salespersons',$data);
        if(empty($res) || (isset($res['salespersons']) && empty($res['salespersons']))){
            $res['success'] = false;
            $res['error'] = config('constants.validation.admin.manager_search_unable');
        } else {
            foreach($res['salespersons'] as $key => $sperson) {
                $salesPerson = SalesPersons::where('person_number',$sperson['salespersonno'])->where('name',$sperson['salespersonname'])->first();
                $res['salespersons'][$key]['is_exist'] = 0 ;
                $res['salespersons'][$key]['is_exist_id'] = 0;
                if($salesPerson){
                    $res['salespersons'][$key]['is_exist'] = 1;
                    $res['salespersons'][$key]['is_exist_id'] = $salesPerson->id;
                }
            }
            $res['success'] = true;
        }
        echo json_encode($res);
        die();
    }
}
