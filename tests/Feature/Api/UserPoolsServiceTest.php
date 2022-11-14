<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;
use App\Models\UserAssetPool;

class UserPoolsServiceTest extends TestCase
{
    use UserTrait;

    public function test_user_pools_service_asset_pools()
    {
        $user = $this->getValidUser();                      

        $response = $this->actingAs($user)->getJson(route('user-pools-service.asset-pools'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.pool_id')
                                ->has('data.0.pool_name')                    
                                ->has('data.0.price_usd')                    
                                ->has('data.0.pool_logo') 
                                ->has('data.0.commission_buy') 
                                ->has('data.1.pool_id')
                                ->has('data.1.price_usd')                    
                                ->has('data.1.pool_name')                    
                                ->has('data.1.pool_logo')
                                ->has('data.1.commission_buy')
                                ->etc();
                }                
            );
    }

    public function test_user_pools_service_rate_pools()
    {
        $user = $this->getValidUser();                      

        $response = $this->actingAs($user)->getJson(route('user-pools-service.rate-pools'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.pool_id')
                                ->has('data.0.pool_name')                    
                                ->has('data.0.price_usd')                    
                                ->has('data.0.pool_logo') 
                                ->has('data.0.pool_rate') 
                                ->has('data.1.pool_id')
                                ->has('data.1.price_usd')                    
                                ->has('data.1.pool_name')                    
                                ->has('data.1.pool_logo')
                                ->has('data.1.pool_rate')
                                ->etc();
                }                
            );
    }

    public function test_user_pools_service_user_asset_pools()
    {
        $user = $this->getValidUser();                      

        $response = $this->actingAs($user)->getJson(route('user-pools-service.user-asset-pools'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.pool_id')
                                ->has('data.0.pool_name')                    
                                ->has('data.0.price_usd')                    
                                ->has('data.0.pool_logo') 
                                ->has('data.0.pool_usd_print') 
                                ->has('data.1.pool_id')
                                ->has('data.1.price_usd')                    
                                ->has('data.1.pool_name')                    
                                ->has('data.1.pool_logo')
                                ->has('data.1.pool_usd_print')
                                ->etc();
                }                
            );
    }

    public function test_user_pools_service_user_rate_pools()
    {
        $user = $this->getValidUser();                      

        $response = $this->actingAs($user)->getJson(route('user-pools-service.user-rate-pools'));

        // $json = $response->decodeResponseJson();
        // dd($json);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.pool_id')
                                ->has('data.0.pool_name')                    
                                ->has('data.0.price_print')                    
                                ->has('data.0.price_usd_print') 
                                ->has('data.0.pool_rate') 
                                ->has('data.1.pool_id')
                                ->has('data.1.price_print')                    
                                ->has('data.1.pool_name')                    
                                ->has('data.1.price_usd_print')
                                ->has('data.1.pool_rate')
                                ->etc();
                }                
            );
    }

    public function test_user_pools_service_user_profile()
    {
        $user = $this->getValidUser();                      

        $response = $this->actingAs($user)->getJson(route('user-pools-service.user-profile'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.balance_usd')
                                ->has('data.balance_usdt')                    
                                ->has('data.balance_usd_print') 
                                ->has('data.balance_usdt_print') 
                                ->where('satatus', 'success')                                
                                ->etc();
                }                
            );
    }
    
    public function test_user_pools_service_pool_balance_update__assets_pool_sell()
    {
        $user = $this->getValidUser();      

        $userAssetPool = UserAssetPool::where('user_id', $user->id)
                                      ->where('asset_pool_id', '>', 0)
                                      ->inRandomOrder()->first();                        

        $data = [
            'pool_type' => 'assets-pool',
            'operation_type' => 'sell',
            'pool_id' => $userAssetPool->asset_pool_id,
            'sum' => round($userAssetPool->price_usd/2, 2),
        ];

        $response = $this->actingAs($user)->postJson(route('user-pools-service.pool-balance-update'), $data);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.balance_usd')
                                ->has('data.balance_usdt')                    
                                ->has('data.balance_usd_print') 
                                ->has('data.balance_usdt_print') 
                                ->where('satatus', 'success')                                
                                ->etc();
                }                
            );
    }

    public function test_user_pools_service_pool_balance_update__assets_pool_buy()
    {
        $user = $this->getValidUser();      

        $userAssetPool = UserAssetPool::where('asset_pool_id', '>', 0)->inRandomOrder()->first();

        $data = [
            'pool_type' => 'assets-pool',
            'operation_type' => 'buy',
            'pool_id' => $userAssetPool->asset_pool_id,
            'sum' => 3.14,
        ];

        $response = $this->actingAs($user)->postJson(route('user-pools-service.pool-balance-update'), $data);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.balance_usd')
                                ->has('data.balance_usdt')                    
                                ->has('data.balance_usd_print') 
                                ->has('data.balance_usdt_print') 
                                ->where('satatus', 'success')                                
                                ->etc();
                }                
            );
    }

    public function test_user_pools_service_pool_balance_update__rate_pool_sell()
    {
        $user = $this->getValidUser();      

        $userAssetPool = UserAssetPool::where('user_id', $user->id)
                                      ->where('rate_pool_id', '>', 0)
                                      ->inRandomOrder()->first();                        

        $data = [
            'pool_type' => 'rate-pool',
            'operation_type' => 'sell',
            'pool_id' => $userAssetPool->rate_pool_id,
            'sum' => round($userAssetPool->price_usd/2, 2),
        ];

        $response = $this->actingAs($user)->postJson(route('user-pools-service.pool-balance-update'), $data);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.balance_usd')
                                ->has('data.balance_usdt')                    
                                ->has('data.balance_usd_print') 
                                ->has('data.balance_usdt_print') 
                                ->where('satatus', 'success')                                
                                ->etc();
                }                
            );
    }

    public function test_user_pools_service_pool_balance_update__rate_pool_buy()
    {
        $user = $this->getValidUser();      

        $userAssetPool = UserAssetPool::where('rate_pool_id', '>', 0)->inRandomOrder()->first();

        $data = [
            'pool_type' => 'rate-pool',
            'operation_type' => 'buy',
            'pool_id' => $userAssetPool->rate_pool_id,
            'sum' => 3.14,
        ];

        $response = $this->actingAs($user)->postJson(route('user-pools-service.pool-balance-update'), $data);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.balance_usd')
                                ->has('data.balance_usdt')                    
                                ->has('data.balance_usd_print') 
                                ->has('data.balance_usdt_print') 
                                ->where('satatus', 'success')                                
                                ->etc();
                }                
            );
    }
}