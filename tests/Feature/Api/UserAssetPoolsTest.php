<?php

namespace Tests\Feature\Api;

use App\Models\UserAssetPool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;

class UserAssetPoolsTest extends TestCase
{
    use UserTrait;

    public function test_user_asset_pools_index()
    {        
        $user = $this->getValidAdminUser(); 

        $userAssetPool = UserAssetPool::inRandomOrder()->first();

        $response = $this->actingAs($user)->getJson(route('user-asset-pools.index', ['user_id' => $userAssetPool->user_id])); 
        
        // $json = $response->decodeResponseJson();
        // dd($json);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.0.user_id')                            
                            ->has('data.0.asset_pool_id')                            
                            ->has('data.0.price_start_usd')                            
                            ->has('data.0.price_usd')                            
                            ->has('data.1.user_id')                            
                            ->has('data.1.asset_pool_id')                            
                            ->has('data.1.price_start_usd')                            
                            ->has('data.1.price_usd');
            });
    }       
}