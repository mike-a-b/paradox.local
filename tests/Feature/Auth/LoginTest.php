<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Tests\Services\UserTrait;
use Illuminate\Support\Str;

class LoginTest extends TestCase
{    
    use UserTrait;
    
    public function test_login_view()
    {
        $response = $this->get(route('login'));

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $user = $this->getValidUser();
        
        $response = $this->actingAs($user)->get(route('login'));        

        $response->assertRedirect(route('home'));
    }

    public function test_user_can_authenticate()
    {
        $user = $this->getValidUser();
        $userCredentials = $this->getValidUserCredentials();
        //dd($user);
        $response = $this->post(route('login'), [
            'email' => $userCredentials->email,
            'password' => $userCredentials->password,
        ]);        
        
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('home'));
    }

    public function test_user_can_not_authenticate()
    {        
        $userCredentials = $this->getValidUserCredentials();
        //dd($user);
        $response = $this->from(route('login'))
                         ->post(route('login'), [
                                'email' => $userCredentials->email,
                                'password' => Str::random(10),
                            ]);        

        $response->assertStatus(302)
                 ->assertSessionHasErrors(['email'])
                 ->assertRedirect(route('login'));
    }
}