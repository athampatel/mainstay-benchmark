<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDetails;
use App\Helpers\SDEApi;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('Auth.sign-in');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();
        $customer = UserDetails::where('user_id',$user->id)
                    ->leftjoin('users','users.id','=','user_details.user_id')
                    ->select('user_details.*','users.profile_image')
                    ->where('user_details.is_active',0)
                    ->get();
                            
        if($user->is_vmi){
            $data = array(            
                "filter" => [
                    [
                        "column"=>"customerno",
                        "type"=>"equals",
                        "value"=>$customer[0]['customerno'],
                        "operator"=>"and"
                    ]
                ],
                "offset" => 1,
                "limit" => 1
            );

            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','Customers',$data);

            if(!empty($response)){
                if(!empty($response['customers'])){           
                    $request->session()->put('vmi_nextonsitedate',Carbon::createFromFormat('Y-m-d',$response['customers'][0]['vmi_nextonsitedate'])->format('d-m-Y'));            
                    $request->session()->put('vmi_physicalcountdate',Carbon::createFromFormat('Y-m-d', $response['customers'][0]['vmi_physicalcountdate'])->format('d-m-Y'));            
                }
            } 
        }
        $request->session()->put('customers',$customer);
        $request->session()->put('customer_no',$customer[0]['customerno']);
        // Add selected customers
        $selected_customer = array();
        foreach($customer as $cs) {
            if($cs['customerno'] == $customer[0]['customerno']){
                $selected_customer = $cs;
            }
        }
        $request->session()->put('selected_customer',$selected_customer);
        // set cookie
        $cookie = cookie('customer_welcome', 'welcome admin', 0.1);

        return redirect()->intended(RouteServiceProvider::HOME)->withCookie($cookie);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
