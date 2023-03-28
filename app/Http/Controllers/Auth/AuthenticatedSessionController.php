<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDetails;
use Session;
use App\Helpers\SDEApi;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{

    public function __construct(SDEApi $SDEApi){
        $this->SDEApi = $SDEApi;
    }
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // return view('auth.login');
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
            $response   = $this->SDEApi->Request('post','Customers',$data);
            
            // Carbon::createFromFormat('Y-m-d',  '19/02/2019')->format('d-m-Y'); 
            if(!empty($response)){
                if(!empty($response['customers'])){
                    // $request->session()->put('vmi_nextonsitedate',$response['customers'][0]['vmi_nextonsitedate']);            
                    // $request->session()->put('vmi_physicalcountdate',$response['customers'][0]['vmi_physicalcountdate']);            
                    $request->session()->put('vmi_nextonsitedate',Carbon::createFromFormat('Y-m-d',$response['customers'][0]['vmi_nextonsitedate'])->format('d-m-Y'));            
                    $request->session()->put('vmi_physicalcountdate',Carbon::createFromFormat('Y-m-d', $response['customers'][0]['vmi_physicalcountdate'])->format('d-m-Y'));            
                }
            } 
        }
        $request->session()->put('customers',$customer);
        $request->session()->put('customer_no',$customer[0]['customerno']);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // dd($request);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
