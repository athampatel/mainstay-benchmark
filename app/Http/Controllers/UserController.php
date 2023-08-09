<?php

namespace App\Http\Controllers;

use App\Models\CustomerMenu;
use App\Models\CustomerMenuAccess;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\SalesPersons;
use App\Models\UserSalesPersons;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Customer as CustomerUnqiue;

class UserController extends Controller
{
    public static function createUser($data,$action = 0){        
      //  dd($data); 

        $useremail  = isset($data['emailaddress']) ? $data['emailaddress'] : $data['email'];

        $contact_email = isset($data['contactemail']) ? $data['contactemail'] : $data['emailaddress']; 
        $contact_name = isset($data['contactname']) ? $data['contactname'] : $data['customername']; 

        $userData = array(  'name'              => $contact_name,
                            // 'email'             => $useremail,
                            // 'email'             => $contact_email ? $contact_email : $useremail, // line to be changed
                            'email'             => $contact_email, // line to be changed
                            // 'password'          => Hash::make('sde@123'),
                            'password'          => Hash::make($data['vmi_password']),
                            'activation_token'  => '',
        );
        // $user = User::where('email',$useremail)->first();
        $userData['is_temp'] = 0;
        if(isset($data['is_temp']) || isset($data['temp']))
            $userData['is_temp'] = 1;

       
        $user = User::where('email',$contact_email)->first();
        if(!$user){
            $user = User::create($userData);
        }
        if($action == 1 &&  $userData['is_temp'] == 0) {
            $user->activation_token = Str::random(40);
            $user->active = 1;
            $user->save();
        }elseif($userData['is_temp'] == 1 ){
            $user->is_temp = 1;
            $user->save();
        }
        if($user){
            if($data['vmi_companycode'] != ""){
                $user->is_vmi = 1;
                $user->save();
            }
            
            $menus = CustomerMenu::all()->pluck('id');
            $access = "";
            $total_menus = count($menus);
            $i = 1;
            foreach($menus as $menu){
                if($i == $total_menus){
                    $access .= "$menu";
                } else {
                    $access .= "$menu,";
                }
            }
            CustomerMenuAccess::create([
                'user_id' => $user->id,
                'access' => $access
            ]);
        }

        if(!$user) return false;

        $user_details = UserDetails::where('user_id',$user->id)->where('customerno',$data['customerno'])->get()->first();

        $user_data = array( 'user_id'           => $user->id,
                            'ardivisionno'      => $data['ardivisionno'],
                            'customerno'        => $data['customerno'],
                            'customername'      => $data['customername'],
                            'addressline1'      => $data['addressline1'],
                            'addressline2'      => $data['addressline2'],
                            'addressline3'      => $data['addressline3'],
                            'vmi_companycode'   => $data['vmi_companycode'],
                            'city'              => $data['city'],
                            'state'             => $data['state'],
                            'zipcode'           => $data['zipcode'],
                            'email'             => $useremail,
                            'phone_no'          => isset($data['phone_no']) && $data['phone_no']!='' ? $data['phone_no'] : '',
                            'contactcode'       => isset($data['contactcode']) ? $data['contactcode'] : '',
                            'contactname'       => isset($data['contactname']) ? $data['contactname'] : '',
                        );
        /* checks in the customer table */
        // $data['customerno']
        $is_found = CustomerUnqiue::where('customerno',$data['customerno'])->first();
        /* checks and add the customer table */

        if(empty($user_details))
            $user_details = UserDetails::create($user_data);
        else
            $user_details->save($user_data);

        $user['details_id'] = $user_details->id;
        
        if(!$is_found) {
            CustomerUnqiue::create([
                'customerno' => $data['customerno']
            ]);
        }
        
        if(!$user_details) return false;
        
        return $user;
    }

    public static function CreateCucstomerDetails($data = null,$user_id = 0){
        $user_data = array( 'user_id'           => $user_id,
                            'ardivisionno'      => $data['ardivisionno'],
                            'customerno'        => $data['customerno'],
                            'customername'      => $data['customername'],
                            'addressline1'      => $data['addressline1'],
                            'addressline2'      => $data['addressline2'],
                            'addressline3'      => $data['addressline3'],
                            'vmi_companycode'   => $data['vmi_companycode'],
                            'city'              => $data['city'],
                            'state'             => $data['state'],
                            'zipcode'           => $data['zipcode'],
                            // 'email'             => isset($data['emailaddress']) ?  $data['emailaddress'] : '',
                            // 'email'             => isset($data['emailaddress']) || () ?  $data['emailaddress'] : $data['email'],
                            'email'             => isset($data['emailaddress']) ?  $data['emailaddress'] : '',
                            'phone_no'          => isset($data['phone_no']) && $data['phone_no'] !='' ? $data['phone_no'] : '',
                            'contactname'       => isset($data['contactname']) ? $data['contactname'] : '',
                            'contactcode'       => isset($data['contactcode']) ? $data['contactcode'] : '',
                        );

        $user_details = UserDetails::where('customerno',$data['customerno'])->where('user_id',$user_id)->first();
        if(!empty($user_details)){
            unset($user_data['customerno']);
            $user_details->save($user_data);
        }else{
            $user_details = UserDetails::create($user_data);
            $is_found = CustomerUnqiue::where('customerno',$data['customerno'])->first();
                if(!$is_found) {
                    CustomerUnqiue::create([
                        'customerno' => $data['customerno']
                    ]);
                }
        }
        $sales_person = array();
        if($data['salespersonemail'] != '')
            $sales_person = SalesPersons::where('email',$data['salespersonemail'])->first();
        if(empty($sales_person)) 
            $sales_person = SalesPersonController::createSalesPerson($data);
        if($sales_person){
            $user_sales_persons = UserSalesPersons::where('user_details_id',$user_details['id'])->where('sales_person_id',$sales_person['id'])->first();
            if(empty($user_sales_persons)){
                UserSalesPersons::create([
                    'user_details_id' => $user_details['id'],
                    'sales_person_id' => $sales_person['id']
                ]);
            }
        }
        return true;

    }
}
