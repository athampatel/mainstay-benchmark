<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use App\Models\SalesPersons;
use App\Models\UserDetails;
use App\Models\UserSalesPersons;
use App\Http\Controllers\NotificationController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Models\SignupRequest;

class AuthController extends Controller
{
    public function __construct(SDEApi $SDEApi){
        $this->SDEApi = $SDEApi;
        //$this->emailHelper = $emailHelper;
    }

    public function autheticate( Request $request ){

        $token = $request->input('hash');
        $customer = $request->input('customer');
        if ($token != '' && $customer != '') {            
            $adminUser =  Admin::where('remember_token','like', $token)->get();
        }
       
    }


    protected function autheticate(Request $request, $user){
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        die;
    }


    public static function CreateCustomer($response = null, $action = 0,$postdata = null){
        $_user    = User::where('email',$response['emailaddress'])->first();
        if(!empty($_user)){
            return redirect()->back()->withErrors(['user_exists' => 'Account already exists']); 
        }  
        $user   =  UserController::createUser($response,$action,$postdata);

       
        $email = isset($response['emailaddress']) ? $response['emailaddress'] : $response['email'];
        $sales_person = array();
        if($response['salespersonemail'] != '')
            $sales_person = SalesPersons::where('email',$response['salespersonemail'])->first();
        if(empty($sales_person)) 
            $sales_person = SalesPersonController::createSalesPerson($response);
        if($sales_person){
            $user_sales_persons = UserSalesPersons::where('user_details_id',$user['details_id'])->where('sales_person_id',$sales_person['id'])->first();
            if(empty($user_sales_persons)){
                UserSalesPersons::create([
                    'user_details_id' => $user['details_id'],
                    'sales_person_id' => $sales_person['id']
                ]);
            }
        }
       
        $message    = 'Thanks for validating your email address, you will get a confirmation';
        $status     = 'success';        
        $body       = "Hi, <br /> A customer with email address {$email} has requested for member access, Please find the customer details below.<br/>";
        $body       .= "<p><strong>customer No:</strong>".$response['customerno']."</p>"; 
        $body       .= "<p><strong>Customer Name:</strong>".$response['customername']."</p>"; 
        $body       .= "<p><strong>Sales Person No:</strong>".$response['salespersonno']."</p>"; 
        $body       .= "<p><strong>Sales Person Email:</strong>".$response['salespersonemail']."</p>"; 
        $sp_email   = $response['salespersonemail'];
        $link       = "/fetch-customer/{$email}";
        return array('body' => $body,'link' => $link,'status' => $status,'sp_email' => $sp_email,'message' => $message , 'user' => $user );
    }

    // orders@10-spec.com
    public function user_register(Request $request){          
        $request->validate([
            'email'         => ['required', 'string', 'email', 'max:255'],
            'full_name'      => ['required', 'string', 'max:255'],
            'company_name'   => ['required', 'string', 'max:255'], // 'unique:users'
        ]);

        $data = array(            
            "filter" => [
                [
                    "column"=>"emailaddress",
                    "type"=>"equals",
                    "value"=>$request->email,
                    "operator"=>"and"
                ]
            ],
            "offset" => 1,
            "limit" => 5
        );

        $postdata = $request->input();

        $user       = User::where('email',$request->email)->where('active',0)->first();
        $message    = 'Thanks for validating your email address, you will get a confirmation';
        $status     = 'success';
        $_multiple  = 0;

        $details    = array('subject'   => 'New customer request for member portal access',
                            'title'     => 'Customer Portal Access');
        
        $uniqueId   = $request->email;
        $request_id = 0;

        $body      = "<p>A customer with email address {$request->email} has requested for member portal access.<br/> Customer Details</p>";

       // $body   .= '<table border="0" align="left" width="160" cellpadding="0" cellspacing="0" bgcolor="424448">';
        $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Customer Name:</span><span>'.$request->full_name.'</span></p>';
        $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Company Name:</span><span>'.$request->company_name.'</span></p>';
        $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Phone No:</span><span>'.$request->phone_no.'</span></p>';
        $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Email Address:</span><span>'.$request->email.'</span></p>'; 
       // $body   .= '</table>';
        $details['body'] = $body;  

        if(empty($user)){
            $response   = $this->SDEApi->Request('post','Customers',$data); 
            $message    = '';
            $status     = 'error';            
            $_details   = array();
            $error      = 1;
            $_multiple  = 0;            
            if (!empty($response['customers'])){
                if ( count($response['customers']) === 1 ) {
                    $response   = $response['customers'][0];
                    $_details   = self::CreateCustomer($response,0,$postdata);  
                    if(is_array($_details))                  
                        $details    = array_merge($details,$_details);                        
                    else
                        return $_details;
                        $error      = 0;
                }elseif(count($response['customers']) > 1){
                    $_multiple  = 1;
                }
            }
           // if($error){
                    
                    $signupdata     =   array(  'full_name'     => $request->full_name,
                                                'company_name'  => $request->company_name,
                                                'email'         => $request->email,
                                                'phone_no'      => $request->phone_no);
                    $data_request   = SignupRequest::create($signupdata);   
                    $request_id     = $data_request->id;            
                    $link           = "/fetch-customer/{$request->email}?req=".$data_request->id;
            //}
            $details['title'] = "New customer request for portal access";   
            $details['subject'] = "New customer request for member portal access";
           
            if($_multiple){
                $link .= "&duplicate=".$_multiple;
            }
              
            $details['status']    = 'success';

            $details['message']   = 'Your request for member access has been submitted successfully, you will get a confirmation';
            
            $message    = isset($details['message']) ? $details['message'] : '';
            $status     = isset($details['status']) ? $details['status'] : '';
            //$user       = User::where('email',$request->email)->first();           
        }
        if($user){
            $user->activation_token = Str::random(30);
            $user->save();
            $uniqueId  = $user->id;
        }
        $admin      = Admin::first(); 

        if($admin){    
            $url    = env('APP_URL').'admin/user/'.$uniqueId.'/change-status/'.$admin->unique_token.'?code=1';

            if($request_id)
                $url .= '&request='.$request_id;
            if($_multiple)
                $url .= '&duplicate=1';

           /* $params = array('mail_view' => 'emails.email-body', 
                            'subject'   => 'New user Signup request', 
                            'url'       => $url);   */
            $details['link']            =  $url;      
            $details['mail_view']       =  'emails.email-body';             
            $_notification = array( 'type'      => 'signup',
                                    'from_user'  => $uniqueId,
                                    'to_user'  => 0,
                                    'text'      => $message,
                                    'action'    => $url,
                                    'status'    => 0,
                                    'is_read'   => 0,
                                    'request_id' => $request_id);                

            $notification = new NotificationController();                        
            $notification->create($_notification);

            \Mail::to('atham@tendersoftware.in')->send(new \App\Mail\SendMail($details));
            // \Mail::to('atham@tendersoftware.in')->send(new \App\Mail\SendMail($params));
            \Mail::to('gokulnr@tendersoftware.in')->send(new \App\Mail\SendMail($details));
        }


        return redirect()->back()->with($status, $message);
    }


    public function user_login(Request $request,LoginRequest $loginRequest){
        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
            'active' => 1,
        ];

        $loginRequest->ensureIsNotRateLimited();

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($loginRequest->throttleKey());
            $loginRequest->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {

            $credentials1 = [
                'email' => $request['email'],
                'password' => $request['password'], 
            ];
            
            if(!Auth::attempt($credentials1)){
                dd('__comes in 1');
                RateLimiter::hit($loginRequest->throttleKey());
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                    // 'active' => trans('auth.active'),
                ]);
            } else {
                // dd('__comes in 2');
                RateLimiter::hit($loginRequest->throttleKey());
                throw ValidationException::withMessages([
                    // 'email' => trans('auth.failed'),
                    'active' => trans('auth.active'),
                ]);
            }
        }
    }
    public function logout(request $reuest){
        Auth::guard('user')->logout();
        return redirect('/');
    }
}
