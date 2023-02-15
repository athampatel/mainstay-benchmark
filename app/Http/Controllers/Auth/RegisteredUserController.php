<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{

    public function __construct(AuthController $authController) {
        $this->authController = $authController;
    }


    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // return view('auth.register');
        return view('Auth.sign-up');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //     ]);
        
    //     // api request work start
    //     $isUser = $this->authController->user_register($request->email);
    //     dd($isUser);
    //     die();
    //     // api request work end
    //     $user = User::create([
    //         'name' => 'gokul',
    //         'email' => $request->email,
    //         'password' => Hash::make('gokul@123'),
    //     ]);

    //     dd($user);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     return redirect(RouteServiceProvider::HOME);
    // }
}
