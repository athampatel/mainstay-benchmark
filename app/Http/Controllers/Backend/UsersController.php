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
use App\Http\Controllers\Backend\DashboardController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Models\ChangeOrderRequest;
use App\Models\ChangeOrderItem;
use App\Http\Controllers\AdminOrderController;
use App\Models\SignupRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class UsersController extends Controller
{
    public $user;  
    public $superAdmin; 
    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
        $this->middleware(function ($request, $next) {
            // $this->user = Auth::guard('admin')->user(); // null
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
        $limit = $request->input('limit');
        if(!$limit){
            $limit = 10;
        }  
        $search = $request->input('search');
        $user =  $this->user;    

        $lblusers = User::leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.user_id','=','user_sales_persons.user_id')
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
                    $lblusers->where('users.is_vmi','=',1);
                    break;    
                default:
                    break;
            }
        }
        if($search){
            $lblusers->where(function($lblusers) use($search){
				$lblusers->orWhere('users.name','like','%'.$search.'%')
                        ->orWhere('users.email','like','%'.$search.'%')
                        ->orWhere('user_details.customerno','like','%'.$search.'%');
			});
        }

        $userss = $lblusers->paginate(intval($limit));
        $paginate = $userss->toArray();
        $paginate['links'] = self::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        $users = $lblusers->get([ 'users.*',
                                'user_details.customerno',
                                'user_details.customername',
                                'user_details.ardivisionno',
                                'sales_persons.person_number',
                                'sales_persons.name as sales_person']);
                          
        return view('backend.pages.users.index', compact('users','paginate','limit','search'));
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
        $search = $request->input('search');
        $managers = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
                    ->get(['sales_persons.*','admins.id as user_id']);
        $managerss = SalesPersons::leftjoin('admins','sales_persons.email','=','admins.email')
        ->paginate(intval($limit));
        $paginate = $managerss->toArray();
        $paginate['links'] = UsersController::customPagination(1,$paginate['last_page'],$paginate['total'],$paginate['per_page'],$paginate['current_page'],$paginate['path']);
        // return view('backend.pages.managers.index', compact('managers'));
        return view('backend.pages.managers.index', compact('managers','search','paginate','limit'));
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
        return view('backend.pages.users.create', compact('roles','email'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation Data


       
        $postdata       = $request->input();
        $is_duplicate   = 0;
        if(!isset($postdata['create_user'])){
            $request->validate([
                'customername' => 'required|max:50',
                'email' => 'required|max:100|email|unique:users',
                'salespersonno' => 'required|min:1',
            ]);

            $postdata['emailaddress'] = $postdata['email'];
            $response = $this->CreateCustomer($postdata);
           
        }else{
           // $email_address  = $postdata['email'];
            $duplicate      = array();
            $customer       = array();
            $create_user    =  $postdata['create_user'];

            foreach($create_user as $key => $value){
                $is_duplicate = 0;
                $email        = $postdata['emailaddress'][$key];

                if(!in_array($email,$duplicate)){
                    $duplicate[]    = $email;
                }else{
                    $is_duplicate = 1;  
                }
                if(!$is_duplicate){                      
                    foreach($postdata as $data_key => $_val){
                        if($data_key == '_token')
                            $customer[$key][$data_key] = $_val;
                        else
                            $customer[$key][$data_key] = $_val[$key];
                    }
                }
            }
            if(!empty($customer)){
                foreach($customer as $_customer){
                    $response = $this->CreateCustomer($_customer);
                }
            }
            if($is_duplicate){
                session()->flash('warning', 'Customer has been created, excluding duplicate email address');
                  return redirect()->route('admin.users.index');
            }
        }
        

        // Create New User
        /*$user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }*/
        $status  = isset($response['status']) ? $response['status'] : '';

        session()->flash($status, 'Customer has been created !!');
        return redirect()->route('admin.users.index');
    }

    public function CreateCustomer($postdata = null){

        $response   = AuthController::CreateCustomer($postdata,1);
        $user       = isset($response['user']) ? $response['user'] : null;
        $message    = 'Opps something went wrong';
        $status     = 'error';  
        if(!empty($user)){            
            $this->sendActivationLink($user->id);
            $message    = 'User has been created !!';
            $status     = 'success';    
        }
        return array('status' => $status,'message' => $message);
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
                    ->leftjoin('user_sales_persons','user_details.user_id','=','user_sales_persons.user_id')
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
        return view('backend.pages.users.edit', compact('user'));
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

        session()->flash('success', 'User has been updated !!');
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

        session()->flash('success', 'User has been deleted !!');
        return back();
    }

    // get customer information
    public function getCustomerInfo(Request $request){
        $search_text = $request->search_text;
        // dd($search_text);
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
        $customer = Customer::where('email')->first();
        $res = $this->SDEApi->Request('post','Customers',$data);         
        $res['user'] = $customer; 
        echo json_encode($res);
    }

    public function getUserRequest($user_id,$admin_token){
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
                if($user && $user->active == 0 && $user->activation_token != '')
                    $email_address = $user->email;            
            }else{
                $email_address  = $user_id;
                $user           = array();
            }
            $userinfo           = array();
            if($request_id)
                $userinfo       = SignupRequest::find($request_id)->first();
            else
                $userinfo       = SignupRequest::where('email','abdc@testreq.com')->first();
            

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
                DB::table('password_resets')->insert(
                    // ['email' => $user->email, 'token' => $token, 'created_at' => date('Y-m-d h:i:s')]
                    ['email' => $user->email, 'token' => $_token, 'created_at' => date('Y-m-d h:i:s')]
                );

                $params = array('mail_view' => 'emails.user-active', 
                                'subject' => 'reset password link', 
                                'url' => env('APP_URL').'/reset-password/'.$token.'?email='.$user->email);

                // \Mail::to($user->email)->send(new \App\Mail\SendMail($params));
                \Mail::to('atham@tendersoftware.in')->send(new \App\Mail\SendMail($params));

                // email send work end
                $res = ['success' => true, 'message' =>'Customer activated successfully and email sent'];
            } else {
                $res = ['success' => false, 'message' =>'Customer not found'];
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
            $res = ['success' => true, 'message' =>'Customer Blocked successfully'];
        } else {
            $res = ['success' => false, 'message' =>'Customer not found'];
        }
        echo json_encode($res);
    }

    public function  CustomerInventory($userId) {
        //echo "Update Inventory Here".$userId; die; 
        return view('backend.pages.orders.vmi-inventory');  //,compact('customers','user')
    }

    public function CustomerChangeOrders(){
        $user   = $this->user;                
        if($this->superAdmin){

            $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                ->leftjoin('user_sales_persons','users.id','=','user_sales_persons.user_id')
                                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                ->orderBy('change_order_requests.id','DESC')                                                
                                                ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);

        }elseif(DashboardController::isManager($user->id,$user)){
            $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                ->leftjoin('user_sales_persons','users.id','=','user_sales_persons.user_id')
                                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                ->where('sales_persons.id',$user->id)
                                                ->orderBy('change_order_requests.id','DESC')
                                                ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email']);
        }
        return view('backend.pages.orders.index',compact('change_request','user'));
    }

    public function CustomerChangeOrderDetails($change_id){
        //$change_request = ChangeOrderRequest::find($orderId);

        $change_request = ChangeOrderRequest::leftjoin('users','change_order_requests.user_id','=','users.id')
                                                ->leftjoin('user_details','users.id','=','user_details.user_id')
                                                ->leftjoin('user_sales_persons','users.id','=','user_sales_persons.user_id')
                                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                                ->where('change_order_requests.id',$change_id)
                                                ->orderBy('change_order_requests.id','DESC')
                                                ->get(['change_order_requests.*','users.name','users.email','user_details.customerno','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as manager','sales_persons.email as manager_email'])->first();

       // $user_details = UserDetails::where('customerno',$customerno)->first();
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

        $order_detail = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
        if(!empty($order_detail['salesorderhistoryheader'])){
            $order_detail = $order_detail['salesorderhistoryheader'][0];
        } else {
            $order_detail = [];
        }
       

        $changed_items = ChangeOrderItem::where('order_table_id',$change_id)->get();

        return view('backend.pages.orders.change_request',compact('order_detail','changed_items','change_id','change_request'));
    }

    public function ExportAllCustomers(){
        
		$table = User::all();
        $filename = "customers.csv";
        $handle = fopen($filename, 'w+');
		fputcsv($handle, array(
			'name',
			'email'
		));
        foreach($table as $row) {
            fputcsv($handle, array(
                $row->name,
                $row->email,
            )); 
        }
        fclose($handle);
        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, 'customers.csv', $headers);
    }
}
