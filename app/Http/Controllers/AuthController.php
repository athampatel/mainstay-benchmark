<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Models\SalesPersons;
use App\Models\UserDetails;
use App\Models\UserSalesPersons;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Str;

class AuthController extends Controller
{
    public function __construct(SDEApi $SDEApi){
        $this->SDEApi = $SDEApi;
        //$this->emailHelper = $emailHelper;
    }

    // orders@10-spec.com
    public function user_register(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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

        $response   = $this->SDEApi->Request('post','Customers',$data); 
        $message    = '';
        $status     = 'error';
        $details    = array('subject' => 'New customer request for member portal access');
        $body       = '';
        if ( !empty($response['customers']) ) {
            if ( count($response['customers']) === 1 ) {
                $response = $response['customers'][0];
                $user =  UserController::createUser($response);
                if(!$user) return redirect()->back()->withErrors(['user_exists' => 'User already exists']); 
                
                $sales_person = SalesPersons::where('email',$response['salespersonemail'])->first();
                if(!$sales_person) 
                    $sales_person = SalesPersonController::createSalesPerson($response);
                
                if($sales_person){
                    $user_sales_persons = UserSalesPersons::where('user_id',$user['id'])->where('sales_person_id',$sales_person['id'])->first();
                    if(!$user_sales_persons){
                        UserSalesPersons::create([
                            'user_id' => $user['id'],
                            'sales_person_id' => $sales_person['id']
                        ]);
                    }
                }  
                $message    = 'Thanks for validating your email address, you will get a confirmation';
                $status     = 'success';    

                /*
                'user_id' => $user->id,
                    'ardivisionno' => $data['ardivisionno'],
                    'customerno' => $data['customerno'],
                    'customername' => $data['customername'],
                    'addressline1' => $data['addressline1'],
                    'addressline2' => $data['addressline2'],
                    'addressline3' => $data['addressline3'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zipcode' => $data['zipcode'],
                    'email' => $data['emailaddress'],
                */
                
                $body       = "Hi, <br /> A customer with email address {$request->email} has requested for member access, Please find the customer details below.<br/>";
                $body       .= "<p><strong>customer No:</strong>".$response['customerno']."</p>"; 
                $body       .= "<p><strong>Customer Name:</strong>".$response['customername']."</p>"; 
                $body       .= "<p><strong>Sales Person No:</strong>".$response['salespersonno']."</p>"; 
                $body       .= "<p><strong>Sales Person Email:</strong>".$response['salespersonemail']."</p>"; 
                $sp_email   = $response['salespersonemail'];
                $link       = "/fetch-customer/{$request->email}";
                
                //return redirect()->back()->with('success', 'email sent successfully');
            }
        } else {
            $body           = "Hi, <br /> A customer with email address {$request->email} has requested for member access, There were no records found in Sage.";
            $link           = "/fetch-customer/{$request->email}";
            $status         = 'success';
            $message        = 'Your request for member access has been submitted successfully, you will get a confirmation';   
        }   

         $details['title']  = "Customer Portal Access";
         $details['body']   = $body;
         $details['link']   = $link;

        \Mail::to('atham@tendersoftware.in')->send(new \App\Mail\SendMail($details));
        
        return redirect()->back()->with($status, $message);

        // event(new Registered($user));
        // Auth::login($user);
        // return redirect(RouteServiceProvider::HOME);s
    }

}
