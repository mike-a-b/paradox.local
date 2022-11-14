<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class ProfileController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the index page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Setting $settings)
    {
        $settingsList = $settings->getByUserId(auth()->user()->id);
        $debankCheckFrequencySet = $settings->debankCheckFrequencySet;

        return view('admin.profile.index', compact('settingsList', 'debankCheckFrequencySet'));
    }

    public function dosave(Request $request, Setting $settings)
    {                
        $settings->updateProfile(auth()->user()->id, $request);
        
        return redirect()->route('admin.profile.index');
    }    
    /**
     * Show the password page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function password()
    {
        return view('admin.profile.password');
    }

    public function savepassword(Request $request)
    {                
        $password = (string)$request->post('password');

        if (!empty($password)) {
            auth()->user()->setPassword($password);
            
            auth()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');
        }
        
        return redirect()->route('admin.profile.password');
    }
}
