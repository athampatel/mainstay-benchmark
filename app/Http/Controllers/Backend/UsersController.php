<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\SDEApi;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SalesPersons;
use App\Models\User as Customer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\DashboardController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Models\ChangeOrderRequest;
use App\Models\ChangeOrderItem;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PdfController;
use App\Models\CustomerMenu;
use App\Models\CustomerMenuAccess;
use App\Models\Notification;
use App\Models\SignupRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\SchedulerLogController;
use App\Models\UserDetails;
use App\Models\VmiInventoryRequest;
use App\Models\SearchWord;

class UsersController extends Controller
{
    public $user;  
    public $superAdmin; 
    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user(); // null
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
        if(!$limit){
            $limit = 10;
        }  
        $offset     = isset($_GET['page']) ? $_GET['page'] : 0;
        
        if($offset > 1){
            $offset = ($offset - 1) * $limit;
        }
        
        // if($_GET['page'] == 1){
        if(isset($_GET['page']) && $_GET['page'] == 1){ 
            $offset = $offset - 1;
        }

        $search = $request->input('search');
        $user =  $this->user;    

        $lblusers = User::leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id');
       
        if(!$this->superAdmin && !empty($user)){
            $lblusers->leftjoin('admins','sales_persons.email','=','admins.email'); 
            $lblusers->where('admins.id',$user->id);
        }elseif($this->superAdmin && $request->input('manager')){
            $lblusers->leftjoin('admins','sales_persons.email','=','admins.email'); 
            $lblusers->where('admins.id',$request->input('manager'));
        }

        $lblusers->where('users.is_deleted','=',0);

        if($request->input('type')){
            $type = $request->input('type');
            switch($type){
                case 'new':
                    $lblusers->where('users.active','=',0)->where('users.is_deleted','=',0);
                    break;
                case 'vmi':
                    $lblusers->where('user_details.vmi_companycode','!=','');
                    break;    
                default:
                    break;
            }
        }
        if($search){
            $lblusers->where(function($lblusers) use($search){
                $lblusers->orWhere('users.name','like','%'.$search.'%')
                        ->orWhere('users.email','like','%'.$search.'%')
                        ->orWhere('user_details.customerno','like','%'.$search.'%')
                        ->orWhere('user_details.ardivisionno','like','%'.$search.'%')
                        ->orWhere('sales_persons.name','like','%'.$search.'%');
            });
        }
        $lblusers->select(['users.*','user_details.user_id as customer',
                                'user_details.customerno',
                                'user_details.customername',
                                'user_details.ardivisionno',
                                'user_details.vmi_companycode',
                                'sales_persons.person_number',
                                'sales_persons.name as sales_person']); //->groupBy('user_details.user_id');

        $userss = $lblusers->paginate(intval($limit));
        $print_users = $lblusers->orderBy('user_details.customerno', 'asc')->get();
        $users = $lblusers->orderBy('user_details.customerno', 'asc')->offset($offset)->limit($limit)->get();
        $paginate = $userss->toArray();
        $paginate['links'] = self::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        // search words
        // dd($paginate);
        $searchWords = SearchWord::where('type',1)->get()->toArray();
        return view('backend.pages.users.index', compact('users','paginate','limit','search','searchWords','print_users'));
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
        
        if($offset > 1){
            $offset = ($offset - 1) * $limit;
        }
        
        if(isset($_GET['page']) && $_GET['page'] == 1){
            $offset = $offset - 1;
        }
        
        $search = $request->input('search');
        
        if($search) {   
            $managers = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                    ->orWhere('sales_persons.person_number','like','%'.$search.'%')
                    ->orWhere('sales_persons.name','like','%'.$search.'%')
                    ->orWhere('sales_persons.email','like','%'.$search.'%')
                    ->offset($offset)
                    ->limit(intval($limit))
                    ->get(['sales_persons.*','admins.id as user_id']);
            $managerss = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                ->orWhere('sales_persons.person_number','like','%'.$search.'%')
                ->orWhere('sales_persons.name','like','%'.$search.'%')
                ->orWhere('sales_persons.email','like','%'.$search.'%')
                ->paginate(intval($limit));    
        } else {
            $managers = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                    ->offset($offset)
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
        return view('backend.pages.managers.index', compact('managers','search','paginate','limit','searchWords','print_managers'));
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
        $postdata       = $request->input();
        $is_duplicate   = 0;
        $email_address  = '';
        $user_id = 0;
        if(!isset($postdata['create_user'])){
            $request->validate([
                'customername' => 'required|max:50',
                'email' => 'required|max:100|email|unique:users',
                'salespersonno' => 'required|min:1',
            ]);
            $postdata['emailaddress'] = $postdata['email'];
            $response = $this->CreateCustomer($postdata);
            $email_address = $postdata['email'];
            $user_id = $response['id'];
        } else {
            $duplicate      = array();
            $customer       = array();
            $create_user    =  $postdata['create_user'];
            foreach($create_user as $key => $value){
                $is_duplicate = 0;
                $email        = isset($postdata['emailaddress'][$key]) ? $postdata['emailaddress'][$key] : '';

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
                    }
                }
            }                     
            if(!empty($customer)){
                foreach($customer as $insert_key => $_customer){
                    if(!$user_id){
                        $response = $this->CreateCustomer($_customer,$insert_key);
                        if($response['status'] != 0){
                            $user_id  = $response['id'];
                        }
                        if($request->input('send_password')){
                            $details['subject'] = config('constants.email.admin.customer_create.subject');
                            $details['title']   = config('constants.email.admin.customer_create.title');    
                            $details['body']    = "$request->name, <br />Please find you login credetials below <br/> <strong>User Name: </strong/>$request->email.</br>Password: </strong/>".$request->password."<br/>";
                            $details['mail_view']    = "emails.new-account-details";
                            
                            $details['link']    = env('APP_URL').'/';
                        
                            // if(env('APP_ENV') == 'local' || env('APP_ENV') == 'dev'){
                            //     $customer_emails = env('TEST_CUSTOMER_EMAILS');
                            //     $customer_emails = explode(',',$customer_emails);
                            //     foreach($customer_emails as $customer_email){
                            //         Mail::to($customer_email)->send(new \App\Mail\SendMail($details));
                            //     }
                            // }  else {
                            //     Mail::to($request->email)->send(new \App\Mail\SendMail($details));
                            // }
                            $customer_emails = env('TEST_CUSTOMER_EMAILS');
                            $is_local = env('APP_ENV') == 'dev' ? true : false;
                            if($is_local){
                                Mail::bcc(explode(',',$customer_emails))->send(new \App\Mail\SendMail($details));
                            } else {
                                Mail::to($request->email)->send(new \App\Mail\SendMail($details));
                            }
                        }
                    }else{
                       $res = UserController::CreateCucstomerDetails($_customer,$user_id);
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

    public function CreateCustomer($postdata = null,$key = 0){
        $response   = AuthController::CreateCustomer($postdata,1);
        $user       = isset($response['user']) ? $response['user'] : null;
        $message    = config('constants.admin_customer_create.mail.error');
        $status     = 'error';  
        $id         = 0;
        if(!empty($user) && !$key){            
            $this->sendActivationLink($user->id);
            $message    = config('constants.admin_customer_create.mail.success');
            $status     = 'success';    
            $id         = $user->id;            
        }
        return array('status' => $status,'message' => $message,'id' => $id);
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
                          'sales_persons.email as salespersonemail',
                          'sales_persons.name as salespersonname'])->where('users.id',$id)->first();

        $roles  = Role::all();
        $menus = CustomerMenu::all();
        $customer_menu_access = CustomerMenuAccess::where('user_id',$id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
        } else{
            $menu_access = [];
        }
        return view('backend.pages.users.edit', compact('user','menus','menu_access'));
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
        // Create New User
        $user = User::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);

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
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        session()->flash('success', config('constants.customer_update.confirmation_message'));
        return back();
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
            $user['is_deleted'] = 1;
            $user['active'] = 0;
            $user->save();
            //$user->delete();
        }

        session()->flash('success', config('constants.customer_delete.confirmation_message'));
        return back();
    }

    // get customer information
    public function getCustomerInfo(Request $request){
        $search_text = $request->search_text;
        $data = array(            
            "filter" => [
                [
                    "column"=>"emailaddress",
                    "type"=>"equals",
                    "value"=>$search_text,
                    "operator"=>"or"
                ],
                [
                    "column"=>"customerno",
                    "type"=>"equals",
                    "value"=>$search_text,
                    "operator"=>"or"
                ]
            ],
            "offset" => 1,
            "limit" => 100,
        );
        
        $customer = Customer::leftjoin('user_details','users.id','=','user_details.user_id')
                    ->where('users.email',$search_text)
                    ->orWhere('user_details.email',$search_text)
                    ->orWhere('user_details.customerno',$search_text)->first();

        $res = $this->SDEApi->Request('post','Customers',$data);         
        $res['user'] = $customer; 
        echo json_encode($res);
        die();
    }

    public function UserAccessRequest(request $request){
     
        $limit = $request->input('limit');
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
                $users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->orderBy('signup_requests.id','DESC')
                            ->where('signup_requests.status',0)
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
                $users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->orderBy('signup_requests.id','DESC')
                            ->where('signup_requests.status',0)
                            ->offset($offset)
                            ->limit(intval($limit))
                            ->select(['signup_requests.*','users.id as user_id'])
                            ->get();  
                $userss = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->orderBy('signup_requests.id','DESC')
                            ->where('signup_requests.status',0)
                            ->paginate(intval($limit));
            } 
            $print_users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->orderBy('signup_requests.id','DESC')
                            ->where('signup_requests.status',0)
                            ->select(['signup_requests.*','users.id as user_id'])
                            ->get();  
        } else {
            $manager = $this->user;
            if($search) {
                $users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
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
                $users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                            ->leftjoin('user_details','user_details.user_id','=','users.id')
                            ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                            ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                            ->leftjoin('admins','sales_persons.email','=','admins.email')
                            ->where('admins.id',$manager->id)
                            ->orderBy('signup_requests.id','DESC')
                            ->where('signup_requests.status',0)
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
                        ->where('admins.id',$manager->id)
                        ->orderBy('signup_requests.id','DESC')
                        ->where('signup_requests.status',0)
                        ->select(['signup_requests.*','users.id as user_id'])
                        ->get();  
        }
        $paginate = $userss->toArray();
        $paginate['links'] = UsersController::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        return view('backend.pages.users.signup-request',compact('users','search','paginate','limit','print_users')); 
    }

    public function getUserRequest($user_id,$admin_token = ''){
        $is_notification = Notification::where('to_user',0)->where('action',URL::full())->where('status',1)->where('is_read',0)->first();
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
                $userinfo       = SignupRequest::find($request_id)->first();
            else
                $userinfo       = SignupRequest::where('email',$email_address)->first();
            
            
            if(filter_var($email_address, FILTER_VALIDATE_EMAIL)) {               
                $data = array(            
                    "filter" => [
                        [
                            "column"=>"emailaddress",
                            "type"=>"equals",
                            "value"=>$email_address,
                            "operator"=>"and"
                        ],
                    ],
                    "offset" => 1,
                    "limit" => 100,
                );
                $res = $this->SDEApi->Request('post','Customers',$data);
                if(!empty($res['customers'])){                   
                    $customers = $res['customers'];
                }
                Auth::guard('admin')->login($admin);
                return view('backend.pages.users.user_request',compact('customers','user','userinfo')); 
            }
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

                $url = env('APP_URL').'reset-password/'.$token.'?email='.$user->email;
                $details['mail_view']       =  'emails.email-body';
                $details['link']            =  $url;
                $details['namealias'] = 'Hi '.$user->name;
                $details['title']           = config('constants.email.admin.customer_activate.title');   
                $details['subject']         = config('constants.email.admin.customer_activate.subject');
                $body      = config('constants.email.admin.customer_activate.body');
                $details['body'] = $body;
                // if(env('APP_ENV') == 'local' || env('APP_ENV') == 'dev'){
                //     $customer_emails = env('TEST_CUSTOMER_EMAILS');
                //     $customer_emails = explode(',',$customer_emails);
                //     foreach($customer_emails as $customer_email){
                //         Mail::to($customer_email)->send(new \App\Mail\SendMail($details));
                //     }
                // }  else {
                //     Mail::to($user->email)->send(new \App\Mail\SendMail($details));
                // }
                
                $customer_emails = env('TEST_CUSTOMER_EMAILS');
                $is_local = env('APP_ENV') == 'dev' ? true : false;
                if($is_local){
                    Mail::bcc(explode(',',$customer_emails))->send(new \App\Mail\SendMail($details));
                } else {
                    Mail::to($user->email)->send(new \App\Mail\SendMail($details));
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
        $user_details = UserDetails::where('user_id',$userId)->first();
        if($user_details){
            $company_code = $user_details->vmi_companycode;
            $user_detail_id = $user_details->id;
        }
        $constants = config('constants');
        // return view('backend.pages.orders.vmi-inventory',compact('company_code','user_detail_id')); 
        return view('backend.pages.orders.vmi-inventory-list',compact('company_code','user_detail_id','constants')); 
    }

    public function CustomerChangeOrders(Request $request){
        $user   = $this->user;
        $limit = $request->input('limit');
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
                $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                    ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                    ->where(function($change_request) use($search){
                                                        $change_request->orWhere('user_details.customerno','like','%'.$search.'%') 
                                                        ->orWhere('sales_persons.name','like','%'.$search.'%') 
                                                        ->orWhere('user_details.customerno','like','%'.$search.'%') 
                                                        ->orWhere('users.email','like','%'.$search.'%');
                                                    })
                                                    ->orderBy('change_order_requests.id','DESC')
                                                    ->offset($offset)
                                                    ->limit($limit)                                                
                                                    ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
                $change_requests = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                    ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
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
                $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                    ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                    ->orderBy('change_order_requests.id','DESC')
                                                    ->offset($offset)
                                                    ->limit($limit)                                                
                                                    ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
                $change_requests = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                    ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                    ->orderBy('change_order_requests.id','DESC')                                                
                                                    ->paginate(intval($limit));
                $paginate = $change_requests->toArray();
            }
        $print_requests = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                        ->leftjoin('user_details','users.id','=','user_details.user_id')
                        ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                        ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                        ->orderBy('change_order_requests.id','DESC')
                        ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
        // }elseif(DashboardController::isManager($user->id,$user)){
        }else {
            $manager = $this->user;
            if($search) {
                // $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                //                                     ->leftjoin('user_details','users.id','=','user_details.user_id')
                //                                     ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                //                                     ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                //                                     ->where('sales_persons.id',$manager->id)
                //                                     ->where(function($change_request) use($search){
                //                                         $change_request->orWhere('user_details.customerno','like','%'.$search.'%') 
                //                                         ->orWhere('sales_persons.name','like','%'.$search.'%') 
                //                                         ->orWhere('user_details.customerno','like','%'.$search.'%') 
                //                                         ->orWhere('users.email','like','%'.$search.'%');
                //                                     })
                //                                     ->orderBy('change_order_requests.id','DESC')
                //                                     ->offset($offset)
                //                                     ->limit($limit)
                //                                     ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
                $change_request = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
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
                                    ->offset($offset)
                                    ->limit($limit)
                                    ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
                // $change_requests = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                //                                     ->leftjoin('user_details','users.id','=','user_details.user_id')
                //                                     ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                //                                     ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                //                                     ->where('sales_persons.id',$manager->id)
                //                                     ->orderBy('change_order_requests.id','DESC')
                //                                     ->paginate(intval($limit));
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
                // $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                //                                     ->leftjoin('user_details','users.id','=','user_details.user_id')
                //                                     ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                //                                     ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                //                                     ->where('sales_persons.id',$manager->id)
                //                                     ->orderBy('change_order_requests.id','DESC')
                //                                     ->offset($offset)
                //                                     ->limit($limit)
                //                                     ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
                $change_request = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                                    ->leftjoin('users', 'users.id','=','user_details.user_id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('request_status','=',0)
                                    ->where('admins.id',$manager->id)
                                    ->orderBy('change_order_requests.id','DESC')
                                    ->offset($offset)
                                    ->limit($limit)
                                    ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
                // $change_requests = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                //                                     ->leftjoin('user_details','users.id','=','user_details.user_id')
                //                                     ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                //                                     ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                //                                     ->where('sales_persons.id',$manager->id)
                //                                     ->orderBy('change_order_requests.id','DESC')
                //                                     ->paginate(intval($limit));
                $change_requests = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                                    ->leftjoin('users', 'users.id','=','user_details.user_id')
                                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                    ->leftjoin('admins','sales_persons.email','=','admins.email')
                                    ->where('request_status','=',0)
                                    ->where('admins.id',$manager->id)
                                    ->orderBy('change_order_requests.id','DESC')
                                    ->paginate(intval($limit));
                                    // ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
                $paginate = $change_requests->toArray();
            }
            $print_requests = ChangeOrderRequest::leftjoin('user_details','user_details.user_id','=','change_order_requests.user_details_id')
                            ->leftjoin('users', 'users.id','=','user_details.user_id')
                            ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                            ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                            ->leftjoin('admins','sales_persons.email','=','admins.email')
                            ->where('request_status','=',0)
                            ->where('admins.id',$manager->id)
                            ->orderBy('change_order_requests.id','DESC')
                            ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);

        }
        $paginate['links'] = self::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        return view('backend.pages.orders.index',compact('change_request','user','paginate','limit','search','print_requests'));
    }

    public function CustomerChangeOrderDetails($change_id){
        $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                ->where('change_order_requests.id',$change_id)
                                                ->orderBy('change_order_requests.id','DESC')
                                                ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email'])->first();

        $data = array(            
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

        $order_detail = $this->SDEApi->Request('post','SalesOrders',$data);

        if(!empty($order_detail['salesorders'])){
            $order_detail = $order_detail['salesorders'][0];
        } else {
            $order_detail = [];
        }       

        

        $changed_items = ChangeOrderItem::where('order_table_id',$change_id)->get();

        return view('backend.pages.orders.change_request',compact('order_detail','changed_items','change_id','change_request'));
    }

    public function ExportAllCustomers(){
		$customers = User::select('user_details.email','user_details.customerno','user_details.customername','user_details.ardivisionno','sales_persons.name')->leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')->get();
        $filename = "customers.csv";
        $handle = fopen($filename, 'w+');
		fputcsv($handle, array(
			'CUSTOMER NO',
			'CUSTOMER NAME',
			'EMAIL',
			'AR DIVISION NO',
			'BENCHMARK REGIONAL MANAGER',
		));
        foreach($customers as $customer) {
            fputcsv($handle, array(
                $customer->customerno,
                $customer->customername,
                $customer->email,
                $customer->ardivisionno,
                $customer->name,
            )); 
        }
        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'customers.csv', $headers);
    }

    public function ExportAllCustomerInPdf(){
        $customers = User::select('user_details.email','user_details.customerno','user_details.customername','user_details.ardivisionno','sales_persons.name')->leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.id','=','user_sales_persons.user_details_id')
                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')->get()->toArray();
        $filename = "customers.pdf";
        PdfController::generatePdf($customers,$filename);
    }

    // managers to export
    public function ExportAllManagersToExcel(){
        $managers = $this->ExportAllManagersData();
        $filename = "managers.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array(
        'MANAGER NUMBER',
        'MANAGER NAME',
        'MANAGER EMAIL'
        ));
        foreach($managers as $manager) {
        fputcsv($handle, array(
            $manager['person_number'],
            $manager['name'],
            $manager['email']
        )); 
        }
        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'managers.csv', $headers);
    }

    public function ExportAllManagersToPdf(){
        $managers = $this->ExportAllManagersData();
        $name = 'usermanagers.pdf';
        PdfController::generatePdf($managers,$name);
    }

    public function ExportAllManagersData(){
        $managers = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
        ->get(['sales_persons.person_number','sales_persons.name','sales_persons.email'])->toArray();
        return $managers;
    }

    // orders
    public function ExportAllOrdersToExcel(){
        $change_requests = $this->ExportAllOrdersData();
        $filename = "change-orders.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array(
        'CUSTOMER NUMBER',
        'CUSTOMER NAME',
        'CUSTOMER EMAIL',
        'ORDER DATE',
        'REGION MANAGER',
        'ORDER NUMBER'
        ));
        foreach($change_requests as $change_request) {
        fputcsv($handle, array(
            $change_request['customerno'],
            $change_request['name'],
            $change_request['email'],
            $change_request['ordered_date'],
            $change_request['manager'],
            $change_request['order_no'],
        )); 
        }
        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'change-orders.csv', $headers);
    }

    public function ExportAllOrdersToPdf(){
        $change_request = $this->ExportAllOrdersData();
        $name = 'change-order-lists.pdf';
        PdfController::generatePdf($change_request,$name);
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

        return $change_request;
    }

    // signup requests
    public function ExportAllSignupToExcel(){
        $users = $this->ExportSignupData();
        $filename = "sign-up-requests.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array(
        'FULL NAME',
        'COMPANY NAME',
        'EMAIL',
        'PHONE NUMBER',
        ));
        foreach($users as $user) {
        fputcsv($handle, array(
            $user['full_name'],
            $user['company_name'],
            $user['email'],
            $user['phone_no'],
        )); 
        }
        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'sign-up-requests.csv', $headers);
    }
    public function ExportAllSignupToPdf(){
        $users = $this->ExportSignupData();
        $name = 'sign-up-requests.pdf';
        PdfController::generatePdf($users,$name);
    }

    public function ExportSignupData(){
        $users = SignupRequest::leftjoin('users','signup_requests.email','=','users.email')
                    ->orderBy('signup_requests.id','DESC')
                    ->where('signup_requests.status',0)
                    ->select(['signup_requests.full_name','signup_requests.company_name','signup_requests.email','signup_requests.phone_no'])
                    ->get()->toArray();
        return $users;
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
        $ignores = intval($data['ignores']);
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
        $sdeApi = new SDEApi();
        $response = $sdeApi->Request('post','Products',$data);
        
        // Remove unwanted products
        $count = 0;
        foreach($response['products'] as $key => $product){
            $string = $product['itemcode'];
            if (substr($string, 0, 1) === '/') {
                $count = $count + 1;
                unset($response['products'][$key]);
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
            $response1 = $sdeApi->Request('post','Products',$data1);
            $response['products'] = array_merge($response1['products'],$response['products']);    
        }
        
        // total 
        $response['meta']['records'] = $response['meta']['records'] - $count;
        $response['meta']['records'] = $response['meta']['records'] - $ignores;
        
        // offset
        $response['meta']['offset'] = $response['meta']['offset'] - $ignores;
        $response['meta']['offset'] = $response['meta']['offset'] - $ignores;
        
        $response['products'] = array_values($response['products']);

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
        foreach($value_changes as $key => $value_change){
            $VmiInventoryRequest = VmiInventoryRequest::create([
                'company_code' => $company_code,
                'item_code' => $key,
                'user_detail_id' => $user_detail_id,
                'old_qty_hand' => $value_change['old_qty'],
                'new_qty_hand'=> $value_change['new_qty'],
                'change_user' => $user->id 
            ]);
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
        $handle = fopen($filename, 'w+');
		fputcsv($handle, array(
			'ITEM CODE',
			'ITEM CODE DESCRIPTION',
			'VENDOR NAME',
			'QUANTITY ON HAND',
			'QUANTITY PURCHASED',
		));
        foreach($products as $product) {
            fputcsv($handle, array(
                $product['itemcode'],
                $product['itemcodedesc'],
                $product['vendorname'],
                $product['quantityonhand'],
                $product['quantitypurchased'],
            )); 
        }

        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'vmi_inventory.csv', $headers);
    }
}
