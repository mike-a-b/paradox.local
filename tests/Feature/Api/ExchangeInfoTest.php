<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;

class ExchangeInfoTest extends TestCase
{
    use UserTrait;

    public function test_exchange_info_index()
    {
        //$user = $this->getValidUser();        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('exchange-info.index'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.balance_usd')
                                ->has('data.users_balance_usd')
                                ->has('data.commissions_usd')                    
                                ->has('data.commissions_usd')                    
                                ->has('data.updated_at')                    
                                ->has('data.withdraws_usd');                    
                }                
            );
    }
}