<?php

namespace Tests\Services;

use App\Models\User;

trait UserTrait {
    
    protected function getValidUser()
    {
        $user = User::where('email', 'admin@scan-sport.com')->first();

        //dd($user->toArray());

        return $user;
    }

    protected function getValidAdminUser()
    {
        $user = User::where('email', 'admin@admin.com')->first();

        //dd($user->toArray());

        return $user;
    }

    protected function getValidUserCredentials()
    {
        $user = ['email' => 'admin@scan-sport.com', 'password' => '444'];

        return (object)$user;
    }
}