<?php

namespace Tests\Feature\Api;

use Facade\FlareClient\Concerns\UsesTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;

class CurrenciesTest extends TestCase
{
    use UserTrait;

    public function test_currencies_index_base()
    {
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson('/api/v1/currencies');        

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.name')
                                ->has('data.0.price_usd');                    
                }                
            );            
    }    
}