<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\UserBaseInfo;

class SettingsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = auth()->user()->id;

        $user = User::where('id', $userId)->first();
        $userProfile = UserProfile::where('user_id', $userId)->first();

        if (!$userProfile) {
            return redirect()->route('home');
        }

        //dd($user->toArray());

        return view('settings.index', compact('user', 'userProfile'));
    }

    public function update(Request $request, UserBaseInfo $userBaseInfo)
    {
        $userId = auth()->user()->id;

        $data = $request->all();
        $userBaseInfo->validator($data, 'update', $userId)->validate();

        $userBaseInfo->commit($data, 'update', $userId);
        
        
        $password = $request->input('password');
        if (!empty($password)) {
            
            Session::flush();        
            Auth::logout();            
            return redirect()->route('login');
        }

        return redirect()->route('settings');
    }
}
