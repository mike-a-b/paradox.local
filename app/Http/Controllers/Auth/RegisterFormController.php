<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterFormController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function register(Request $data)
    {
        $data->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => 1
        ]);
        $userProfile = new UserProfile();
        $userProfile->user_id = $user->id;
        $userProfile->second_name = $data['name'];
        $userProfile->phone = '';
        $userProfile->telegram = '';
        $userProfile->balance_usd = 0;
        $userProfile->balance_usdt = 0;
        $userProfile->save();
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        $credentials = $credentials + ['is_active' => 1];

        //dd($credentials);
        event(new Registered($user));

        if (Auth::attempt($credentials)) {
            $data->session()->regenerate();
        }
        return redirect()->route('home');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function check(Request $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $validator = Validator::make($data->all(), $rules);

        if (count($validator->errors())>0) {
            return response()->json(["validation"=>false,"errors"=>$validator->errors()]);
        }
        return response()->json(["validation"=>true]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }
}
