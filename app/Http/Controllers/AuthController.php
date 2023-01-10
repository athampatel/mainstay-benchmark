<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Helpers\EmailHelper;
use App\Models\SalesPersons;
use App\Models\UserDetails;
use App\Models\UserSalesPersons;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Str;

class AuthController extends Controller
{
    public function __construct(SDEApi $SDEApi,EmailHelper $emailHelper){
        $this->SDEApi = $SDEApi;
        $this->emailHelper = $emailHelper;
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
		
	
        $response = $this->SDEApi->Request('post','Customers',$data); 

        if(!empty($response['customers'])){
            if(count($response['customers']) === 1){
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

                return redirect()->back()->with('success', 'email sent successfully');
            }
        } else {
            return redirect()->back()->with('success', 'email sent successfully');
        }   
        // event(new Registered($user));
        // Auth::login($user);
        // return redirect(RouteServiceProvider::HOME);s
    }

}
