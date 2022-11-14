<?php

namespace Tests\Feature\Api;

use App\Models\UserBalanceHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;

class UserBalanceHistoryTest extends TestCase
{
    use UserTrait;

    public function test_user_balance_history_index_admin()
    {         
        $user = $this->getValidAdminUser();    
        
        $userBalanceHistory = UserBalanceHistory::inRandomOrder()->first();
        
        $response = $this->actingAs($user)->getJson(route('user-balance-history.index', ['user_id' => $userBalanceHistory->user_id, 'period' => '3m']));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.total_balance_usd')
                                ->has('data.0.created_at')                                                    
                                ->has('data.1.total_balance_usd')
                                ->has('data.1.created_at');
                }
            );
    }

    public function test_user_balance_history_index_user()
    {         
        $user = $this->getValidUser();        
        
        $response = $this->actingAs($user)->getJson(route('user-balance-history.index', ['period' => '3m']));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.total_balance_usd')
                                ->has('data.0.created_at')                                                    
                                ->has('data.1.total_balance_usd')
                                ->has('data.1.created_at');
                }
            );
    }

    /**
     * @depends test_user_balance_history_index_user
     */
    public function test_user_balance_history_index_user_denied()
    {         
        $user = $this->getValidUser();        
        
        $response = $this->actingAs($user)->getJson(route('user-balance-history.index', ['user_id' => 33, 'period' => '3m']));

        $response->assertStatus(403);
    }
    
    public function test_user_balance_history_statistics()
    {
        $user = $this->getValidUser();  

        $response = $this->actingAs($user)->getJson(route('user-balance-history.statistics', ['period' => '7d']));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.user_balance_diff_pers')
                                ->has('data.user_balance_diff_usd') ;
                }                
            );
    }
}