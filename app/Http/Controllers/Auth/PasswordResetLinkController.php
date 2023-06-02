<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Backend\UsersController;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('Auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'email' => ['required', 'email'],
        // ]);

        // // We will send the password reset link to this user. Once we have attempted
        // // to send the link, we will examine the response then see the message we
        // // need to show to the user. Finally, we'll send out a proper response.

       
        // $send_to = $request->only('email');       
        // $status = Password::sendResetLink($send_to);

        // return $status == Password::RESET_LINK_SENT
        //             ? back()->with('status', __($status))
        //             : back()->withInput($request->only('email'))
        //                     ->withErrors(['email' => __($status)]);

        $this->validate(
            $request,
            [
                'email' => 'required|email|exists:users',
            ],
            [
                'email.exists' => 'There is no user available in this email'
            ]
        );
        $token = Str::random(30);
        $_token = Hash::make($token);
        $is_password_reset = DB::table('password_resets')->where('email',$request->email)->first();
        if($is_password_reset){
            DB::table('password_resets')->where('email',$request->email)->update(['token' => $_token,'created_at' =>date('Y-m-d h:i:s')]);
        } else {
            DB::table('password_resets')->insert(
                ['email' => $request->email, 'token' => $_token, 'created_at' => date('Y-m-d h:i:s')]
            );
        }
        $user = User::where('email',$request->email)->first();
        $url = config('app.url').'reset-password/'.$token.'?email='.$request->email;
        $details['mail_view']       =  'emails.email-body';
        $details['link']            =  $url;
        $details['namealias'] = 'Hi testdeveloper';
        $details['title']           = config('constants.email.reset_password.title');   
        $details['subject']         = config('constants.email.reset_password.subject');
        $details['is_button_name'] = 'Reset Password';
        $body      = config('constants.email.reset_password.body');
        $details['body'] = $body;
        $customer_emails = config('app.test_customer_email');
        $is_local = config('app.env') == 'local' ? true : false;
        if($is_local){
            UsersController::commonEmailSend($customer_emails,$details);
        } else {
            try {
                Mail::to($request->email)->send(new \App\Mail\SendMail($details));
            } catch (\Exception $e) {
                Log::error('An error occurred while sending the mail: ' . $e->getMessage());
            } 
        }

        return view('emails.email-body',compact('details'));
        $status = 'passwords.sent';
        return back()->with('status', __($status));
    }
}
