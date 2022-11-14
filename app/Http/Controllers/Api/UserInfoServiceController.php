<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Services\UserBaseInfo;

class UserInfoServiceController extends Controller
{    
    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response
     */
    public function profileShow(Request $request)
    {              
        $userId = auth()->id();
        $userProfile = UserProfile::where('user_id', $userId)->firstOrFail();        

        return [
            'data' => $userProfile,
            'satatus' => 'success'
        ];
    }

    /**
     * Update user ava
     *
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response
     */
    public function updateAva(Request $request)
    {
        $userBaseInfo = new UserBaseInfo();
        $userId = auth()->id();
        
        $request->validate([
            'ava' => 'required|image|mimes:png,jpg,jpeg,gif|max:2048',
        ]);

        $avaFile = $request->file('ava');                        

        $userProfile = $userBaseInfo->updateAva($avaFile, $userId);

        return [
            'data' => [
                'ava' => $userProfile->ava,
            ],
            'satatus' => 'success'
        ];
    }

    /**
     * Delete user ava
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAva()
    {        
        $userBaseInfo = new UserBaseInfo();
        $userId = auth()->id();
     
        $userProfile = $userBaseInfo->deleteAva($userId);

        return [
            'data' => [
                'ava' => $userProfile->ava,
            ],
            'satatus' => 'success'
        ];
    }
}