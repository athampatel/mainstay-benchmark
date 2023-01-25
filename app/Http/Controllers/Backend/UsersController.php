<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\SDEApi;
use App\Http\Controllers\Controller;
use App\Models\Admin;
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

class UsersController extends Controller
{


    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::leftjoin('user_details','users.id','=','user_details.user_id')
                    ->leftjoin('user_sales_persons','user_details.user_id','=','user_sales_persons.user_id')
                    ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                    ->get(['users.*','user_details.customerno','user_details.customername','user_details.ardivisionno','sales_persons.person_number','sales_persons.name as sales_person']);

        return view('backend.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  = Role::all();
        return view('backend.pages.users.create', compact('roles'));
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
        //$user = User::find($id);        
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
        return view('backend.pages.users.edit', compact('user', 'roles'));
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
            $user->delete();
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
    $res = $this->SDEApi->Request('post','Customers',$data);   
    echo json_encode($res);
    }

    public function getUserRequest($user_id,$admin_token){
        $admin      = Admin::where('unique_token', $admin_token)->first();
        $customers  = array();
        if(!empty($admin)){
            if(is_numeric($user_id)){
                $user = User::find($user_id);
                if($user && $user->active == 0 && $user->activation_token != '')
                    $email_address = $user->email;            
            }else{
                $email_address = $user_id;
                $user           = array();
            }

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
                return view('backend.pages.users.user_request',compact('customers','user')); 
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
                // email send work start

                // one solution
                
                // $status = Password::sendResetLink(
                //     [ 'email' =>'gokul12@yopmail.com']
                // );
                
                // another solution
                $token = Str::random(30);
                $_token = Hash::make($token);
                DB::table('password_resets')->insert(
                    // ['email' => $user->email, 'token' => $token, 'created_at' => date('Y-m-d h:i:s')]
                    ['email' => $user->email, 'token' => $_token, 'created_at' => date('Y-m-d h:i:s')]
                );

                $params = array('mail_view' => 'emails.user-active', 'subject' => 'reset password link', 'url' => env('APP_URL').'/reset-password/'.$token.'?email='.$user->email);
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
}
