<?php

namespace App\Http\Requests\Auth;

use App\Helpers\SDEApi;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        
        // changed code
        $sdeApi = new SDEApi();
        $data = [
            "index" => "kEmailAddress",
            "filter" => [
                [
                    "column" => "EmailAddress",
                    "type" => "equals",
                    "value" => $this->only('email')['email'],
                    "operator" => "and"
                ]
            ],
        ];
        $is_error = true;
        $response =$sdeApi->Request('post','Contacts',$data);
        if(!empty($response) && isset($response['contacts']) && !empty($response['contacts'])) {
            foreach($response['contacts'] as $contcat){
                if($contcat['vmi_password'] == $this->only('password')['password']) {
                    $is_error = false;
                }
            }            
        } 
        if($is_error) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
                // 'active' => trans('auth.active'),
            ]);
        }
        
        // already exist code
        if (!Auth::attempt(array_merge($this->only('email', 'password'),['active' => 1 ]), $this->boolean('remember'))) {
            // RateLimiter::hit($this->throttleKey());
            // if (Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
                // 'active' => trans('auth.active'),
            ]);
        } 
        

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
