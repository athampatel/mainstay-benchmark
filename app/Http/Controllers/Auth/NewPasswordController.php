<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SDEApi;
use App\Http\Controllers\Controller;
use App\Models\UserDetails;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        
        $is_password_reset = DB::table('password_resets')->where('email',$request->email)->where('token',$request->token)->first();
        $user = User::where('email',$request->email)->first();
        $valid_access = 1;
        if(empty($is_password_reset) || $user->count() <= 0){      
            $valid_access = 0;
           // return back()->withErrors('status', 'password.invalid');
        }
        return view('Auth.reset-password', ['request' => $request,'valid' => $valid_access]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

      //  $inputs =  $request->input();
        
        
        $is_password_reset = DB::table('password_resets')->where('email',$request->email)->where('token',$request->token)->first();
        $user = User::where('email',$request->email)->first();
        if(!empty($is_password_reset) && $user->count() > 0){            
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();
            DB::table('password_resets')->where('email',$request->email)->where('token',$request->token)->delete();
            self::change_vmi_password($user->id,$request->password);
            return back()->with('status',config('constants.customer-signup.password-reset'));       
        }else{        
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    // $sdeApi = new SDEApi();
                    $is_update = self::change_vmi_password($user->id,$request->password);
                    
                    // $user_detail = UserDetails::where('user_id',$user->id)->get()->toArray();
                    // if(!empty($user_detail)) {
                    //     foreach($user_detail as $us_detail) {
                    //         $data = [
                    //             "method" => "POST",
                    //             "ARDivisionNo" => $us_detail['ardivisionno'],
                    //             "CustomerNo"=> $us_detail['customerno'],
                    //             "ContactCode" => $us_detail['contactcode'],
                    //             "UDF_VMI_Password" => $request->password
                    //         ];
                    //         $response = $sdeApi->Request('post','Contacts',$data);
                    //         if(!empty($response) && isset($response['contacts']) && !empty($response['contacts'])) {
                    //             if($response['contacts'][0]['action'] == 'updated' &&  $response['contacts'][0]['vmi_password'] == $request->password) {
                    //                 $is_update = true;
                    //             }
                    //         }    
                    //     }
                    // }
                    if($is_update) {
                        $user->forceFill([
                            'password' => Hash::make($request->password),
                            'remember_token' => Str::random(60),
                        ])->save();
                    }

                    event(new PasswordReset($user));
                }
            );
            return $status == Password::PASSWORD_RESET
                        ? back()->with('status', __($status))
                        : back()->withInput($request->only('email'))
                                ->withErrors(['email' => __($status)]);
    }
       
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        // return $status == Password::PASSWORD_RESET
        //             ? redirect()->route('login')->with('status', __($status))
        //             : back()->withInput($request->only('email'))
        //                     ->withErrors(['email' => __($status)]);
       /* return $status == Password::PASSWORD_RESET
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);*/
                 
    }

    public static function change_vmi_password($user_id,$password){
        $sdeApi = new SDEApi();
        $user_detail = UserDetails::where('user_id',$user_id)->get()->toArray();
        $is_update = false;
        if(!empty($user_detail)) {
            foreach($user_detail as $us_detail) {
                $data = [
                    "method" => "POST",
                    "ARDivisionNo" => $us_detail['ardivisionno'],
                    "CustomerNo"=> $us_detail['customerno'],
                    "ContactCode" => $us_detail['contactcode'],
                    "UDF_VMI_Password" => $password
                ];
                $response = $sdeApi->Request('post','Contacts',$data);               
                if(!empty($response) && isset($response['contacts']) && !empty($response['contacts'])) {
                    if($response['contacts'][0]['action'] == 'updated' &&  $response['contacts'][0]['vmi_password'] == $password && $is_update == false) {
                        $is_update = true;
                    }
                }    
            }
        }
        return $is_update;
    }
}