<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        //$this->middleware('guest:admin')->except('logout');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //dd($request->toArray());
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $credentials + ['is_active' => 1];

        //dd($credentials);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function showLoginForm()
    {
        if (Auth::user()) {    
            if (!Auth::user()->is_admin) {
                return redirect()->route('home');
            } else {
                return redirect()->route('admin.dashboard.index');
            }
        }
        
        return view('auth.login');
    }

    public function showAdminLoginForm()
    {
        if (Auth::id()) {
            return redirect()->route('admin.dashboard.index');
        }

        return view('admin.auth.login');
    }

    public function adminLogin(Request $request)
    {        
        $credentials = $this->validate($request, [
            'email'   => 'required|email',
            //'password' => 'required|min:6'
            'password' => 'required|min:2'
        ]);       
        
        $credentials = $credentials + ['is_active' => 1];

        //dd($credentials);

        if (Auth::attempt($credentials, $request->get('remember'))) {
            
            return redirect()->route('admin.dashboard.index');

            //return redirect()->intended('/admin-ko');            
        }
        return back()->withInput($request->only('email', 'remember'));
    }

    public function adminLogout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return redirect('/admin/login');        
    }
}