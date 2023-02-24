<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\SalesPersons;
use App\Models\UserSalesPersons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public static function createUser($data,$action = 0){        

        $userData = array(  'name'              =>$data['customername'],
                            'email'             => $data['emailaddress'],
                            'password'          => Hash::make('sde@123'),
                            'activation_token'  => '',
                            // 'activation_token' => Str::random(30),
        );

        $user = User::create($userData);
        if($action == 1) {
            $user->activation_token = Str::random(40);;
            $user->active = 1;
            $user->save();
        }
        if($user){
            // if(array_key_exists('vmi_companycode', $data) || (array_key_exists('is_vmi', $data) && $data['is_vmi'] == 1 )){
            if($data['vmi_companycode'] != ""){
                $user->is_vmi = 1;
                $user->save();
            }
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
                            'email'             => $data['emailaddress']);
       
        if(empty($dataUser))
            $user_details = UserDetails::create($user_data);
        else
            $user_details->save($user_data);

        $user['details_id'] = $user_details->id;
    
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
                            'email'             => isset($data['emailaddress']) ?  $data['emailaddress'] : '');

        $user_details = UserDetails::create($user_data);
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
