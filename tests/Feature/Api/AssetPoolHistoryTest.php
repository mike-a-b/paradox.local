<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;
use App\Models\AssetPool;

class AssetPoolHistoryTest extends TestCase
{
    use UserTrait;

    public function test_asset_pool_history_index_base()
    {
        $user = $this->getValidUser();                

        $assetPool = AssetPool::where('is_active', 1)->first();

        $response = $this->actingAs($user)->getJson(route('asset-pool-history.index', ["pool_id={$assetPool->id}&period=3m"]));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.price_usd')
                                ->has('data.0.created_at');                    
                }                
            );
    }

    /**
     * @depends test_asset_pool_history_index_base
     */
    public function test_asset_pool_history_statistics() 
    {          
        $user = $this->getValidUser();                

        $assetPool = AssetPool::where('is_active', 1)->first();       

        $response = $this->actingAs($user)->getJson(route('asset-pool-history.statistics', ["pool_id={$assetPool->id}&period=3m"]));  

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.new_price')
                            ->has('data.old_price')               
                            ->has('data.direction')                    
                            ->has('data.fraction');                    
            }                
        );
    }
}