<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use App\Models\SalesPersons;
use App\Models\UserSalesPersons;
use App\Http\Controllers\NotificationController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SignupRequest;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function autheticate( Request $request ){
        $token = $request->input('hash');
        $customer = $request->input('customer');
        if ($token != '' && $customer != '') {            
            $adminUser =  Admin::where('remember_token','like', $token)->get();
        }   
    }

    public static function CreateCustomer($response = null, $action = 0,$postdata = null){
        // dd($response);
        $email = isset($response['emailaddress']) ? $response['emailaddress'] : $response['email'];
        $_user    = User::where('email',$email)->where('active',1)->first();
        if(!empty($_user)){
            $customer_no = $response['customerno'];
            $is_user_detail = UserDetails::where('customerno',$customer_no)->first();
            if(!$is_user_detail){   
                $user_details = UserDetails::create([
                    'user_id'           => $_user->id,
                    'ardivisionno'      => $response['ardivisionno'],
                    'customerno'        => $response['customerno'],
                    'customername'      => $response['customername'],
                    'addressline1'      => $response['addressline1'],
                    'addressline2'      => $response['addressline2'],
                    'addressline3'      => $response['addressline3'],
                    'vmi_companycode'   => $response['vmi_companycode'],
                    'city'              => $response['city'],
                    'state'             => $response['state'],
                    'zipcode'           => $response['zipcode'],
                    'email'             => $email,
                    'phone_no'          => isset($response['phone_no']) && $response['phone_no'] !='' ? $response['phone_no'] : '',
                    'contactname'       => isset($response['contactname']) ? $response['contactname'] : '',
                    'contactcode'       => isset($response['contactcode']) ? $response['contactcode'] : '',
                ]);
                $sales_person1 = array();
                if($response['salespersonemail'] != '')
                    $sales_person1 = SalesPersons::where('email',$response['salespersonemail'])->first();
                if(empty($sales_person1)) 
                    $sales_person1 = SalesPersonController::createSalesPerson($response);
                if($sales_person1){
                    $user_sales_persons = UserSalesPersons::where('user_details_id',$user_details->id)->where('sales_person_id',$user_details->id)->first();
                    if(empty($user_sales_persons)){
                        UserSalesPersons::create([
                            'user_details_id' => $user_details->id,
                            'sales_person_id' => $sales_person1['id']
                        ]);
                    }
                }
            } else {
                return array('sp_email' => $email,'message' => config('constants.customer_already_exists') , 'user' => $_user,'status' => 0 );
            }
        }  
        $user   =  UserController::createUser($response,$action,$postdata);
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
       
        $message    = config('constants.email.customer.customer_create.message');
        $status     = 'success';        
        $body       = " A customer with email address {$email} has requested for member access, Please find the customer details below.<br/>";
        $body       .= "<p><strong>Customer Number: </strong>".$response['customerno']."</p>"; 
        $body       .= "<p><strong>Customer Name: </strong>".$response['customername']."</p>"; 
        $body       .= "<p><strong>Regional Manager Number: </strong>".$response['salespersonno']."</p>"; 
        $body       .= "<p><strong>Regional Manager Email: </strong>".$response['salespersonemail']."</p>"; 
        $sp_email   = $response['salespersonemail'];
        $link       = "/fetch-customer/{$email}";
        return array('body' => $body,'link' => $link,'status' => $status,'sp_email' => $sp_email,'message' => $message , 'user' => $user );
    }

    public function user_register(Request $request){          
        $request->validate([
            'email'         => ['required', 'string', 'email', 'max:255'],
            'full_name'      => ['required', 'string', 'max:255'],
            'company_name'   => ['required', 'string', 'max:255'],
        ]);

        $is_user = User::where('email',$request->email)->first();
        if($is_user){
            if($is_user->active == 0 && $is_user->is_deleted == 0){
                return back()->withErrors(config('constants.email.customer.customer_create.requested_already'));
            }   
            if($is_user->is_deleted == 1){
                return back()->withErrors(config('constants.email.customer.customer_create.deleted_by_admin'));
            }
        }

        /* get contact information for the email address */
        $sdeApi = new SDEApi();
        $contact_data = [
            "index" => "kEmailAddress",
            "filter" => [
                [   
                    "column" =>  "EmailAddress",
                    "type" => "equals",
                    "value" => $request->email,
                    "operator" => "and"
                ],
            ],
            
        ];
        $contact_information = [];
        $contact_response = $sdeApi->Request('post','Contacts',$contact_data);
        if(!empty($contact_response) && isset($contact_response['contacts']) &&  !empty($contact_response['contacts'])) {
            $contact_information = $contact_response['contacts'][0];
        } 
        $contact_customer_no = "";
        if(!empty($contact_information)) {
            $contact_customer_no = $contact_information['customerno'];
        }
        
        /* get contact information for the email address */
        $data = array(            
            "filter" => [
                // [
                //     "column"=>"emailaddress",
                //     "type"=>"equals",
                //     "value"=>$request->email,
                //     "operator"=>"and"
                // ]
                [
                    "column"=>"customerno",
                    "type"=>"equals",
                    "value"=> $contact_customer_no,
                    "operator"=>"and"
                ]
            ],
            "offset" => 1,
            "limit" => 5
        );
        $postdata = $request->input();
        $user       = User::where('email',$request->email)->where('active',0)->first();
        $message    = config('constants.customer-signup.validation_email');;
        $status     = 'success';
        $_multiple  = 0;
        $details    = array('subject'   => config('constants.customer-signup.mail.subject'),
                            'title'     => config('constants.customer-signup.mail.title'));
        $uniqueId   = $request->email;
        $request_id = 0;
        $body      = "<p>The customer with email address {$request->email} has requested access to the member portal.</p>";
        $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Customer Name:</span>&nbsp;<span>'.$request->full_name.'</span></p>';
        $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Company Name: </span>&nbsp;<span>'.$request->company_name.'</span></p>';
        $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Phone-No: </span>&nbsp;<span>'.$request->phone_no.'</span></p>';
        $body   .= '<p><span style="width:100px;font-weight:bold;font-size:14px;">Email Address: </span>&nbsp;<span>'.$request->email.'</span></p><br>'; 
        $details['body'] = $body;

        if(empty($user)){
            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','Customers',$data); 
            // dd($response);
            $message    = '';
            $status     = 'error';            
            $_details   = array();
            $error      = 1;
            $_multiple  = 0;            
            if (!empty($response['customers'])){
                if (count($response['customers']) === 1 ) {
                    $response   = $response['customers'][0];
                    // dd($response);
                    /* my work start*/
                    $response['vmi_password'] = $contact_information['vmi_password'];
                    $response['phone_no'] = $contact_information['telephoneno1']. ''. $contact_information['telephoneext1'];
                    // $response['emailaddress'] = $response['emailaddress'] ? $response['emailaddress'] : $contact_information['emailaddress'];
                    $response['emailaddress'] = $contact_information['emailaddress'];
                    $response['contactcode'] = $contact_information['contactcode'] ? $contact_information['contactcode'] : '';
                    $response['contactname'] = $contact_information['contactname'] ? $contact_information['contactname'] : '';

                    /* my work end */
                    $_details   = self::CreateCustomer($response,0,$postdata);  
                    if(is_array($_details))
                        $details    = array_merge($details,$_details);
                    else
                        return $_details;

                    $error   = 0;
                } elseif(count($response['customers']) > 1){
                    $_multiple  = 1;
                }
            }
            $signupdata     =   array(  'full_name'     => $request->full_name,
                                        'company_name'  => $request->company_name,
                                        'email'         => $request->email,
                                        'phone_no'      => $request->phone_no);
            $data_request   = SignupRequest::create($signupdata);   
            $request_id     = $data_request->id;  
            if($error){              
                $link           = "/fetch-customer/{$request->email}?req=".$data_request->id;
            } 
            $details['title']   = config('constants.email.customer.customer_create.title');   
            $details['subject'] = config('constants.email.customer.customer_create.subject');
            if($_multiple){
                $link .= "&duplicate=".$_multiple;
            }
            $details['status']    = 'success';
            $details['message']   = config('constants.customer-signup.confirmation_message');
            $message    = isset($details['message']) ? $details['message'] : '';
            $status     = isset($details['status']) ? $details['status'] : '';
        }
        
        $user = User::where('email',$request->email)->where('active',0)->first();
        if($user){
            $user->activation_token = Str::random(30);
            $user->save();
            $uniqueId  = $user->id;
        }

        // email send
        $admin      = Admin::first(); 
        if($admin){    
            $url    = config('app.url').'admin/user/'.$uniqueId.'/change-status/'.$admin->unique_token.'?code=1';

            if($request_id)
                $url .= '&request='.$request_id;
            if($_multiple)
                $url .= '&duplicate=1';
            
            $details['link']            =  $url;      
            $details['mail_view']       =  'emails.email-body'; 
            $_notification = array( 'type'      => 'Sign Up',
                                    'from_user'  => $uniqueId,
                                    'to_user'  => 0,
                                    'text'      => $message,
                                    'action'    => $url,
                                    'status'    => 1,
                                    'is_read'   => 0,
                                    'request_id' => $request_id,
                                    'icon_path' => '/assets/images/svg/sign_up_notification.svg'
                                );                

            $notification = new NotificationController();                        
            $notification->create($_notification);
            $admin_emails = config('app.admin_emails');
            $is_local = config('app.env') == 'local' ? true : false;
            if($is_local){
              Mail::bcc(explode(',',$admin_emails))->send(new \App\Mail\SendMail($details));
            } else {
              $admin_emails = Admin::all()->pluck('email')->toArray();
              Mail::bcc($admin_emails)->send(new \App\Mail\SendMail($details));
            }

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
                RateLimiter::hit($loginRequest->throttleKey());
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            } else {
                RateLimiter::hit($loginRequest->throttleKey());
                throw ValidationException::withMessages([
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
