<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;

class UserController extends Controller
{
    public static function createUser($data,$action = 0){
        

        $userData = array(  'name'              =>$data['customername'],
                            'email'             => $data['emailaddress'],
                            'password'          => Hash::make('sde@123'),
                            'activation_token'  => '',
                            // 'activation_token' => Str::random(30),
        );

        if(!array_key_exists('vmi_companycode', $data)){
            $userData['is_vmi'] = 1;  
        } 

        if( $action ) {
            $userData['activation_token'] = Str::random(40);;
            $userData['active'] = 1;
        }

        $user = User::create($userData);

        if(!$user) return false;

        $user_data = array( 'user_id'       => $user->id,
                            'ardivisionno'  => $data['ardivisionno'],
                            'customerno'    => $data['customerno'],
                            'customername'  => $data['customername'],
                            'addressline1'  => $data['addressline1'],
                            'addressline2'  => $data['addressline2'],
                            'addressline3'  => $data['addressline3'],
                            'city'          => $data['city'],
                            'state'         => $data['state'],
                            'zipcode'       => $data['zipcode'],
                            'email'         => $data['emailaddress']);

        $user_details = UserDetails::create($user_data);

        if(!$user_details) return false;

        return $user;
    }
}
