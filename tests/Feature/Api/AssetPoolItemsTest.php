<?php

namespace Tests\Feature\Api;

use Facade\FlareClient\Concerns\UsesTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\AssetPool;
use App\Models\AssetPoolItem;

use Tests\Services\UserTrait;

class AssetPoolItemsTest extends TestCase
{
    use UserTrait;

    private static $createdItemId = null;

    public function test_asset_pools_items_index_base()
    {        
        $user = $this->getValidAdminUser();   
        
        $assetPool = AssetPool::where('is_active', 1)->inRandomOrder()->first();

        $response = $this->actingAs($user)->getJson(route('asset-pool-items.index', ['pool_id' => $assetPool->id]));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.0.asset_pool_id')
                            ->has('data.0.asset_id')               
                            ->has('data.0.asset_amount')               
                            ->has('data.0.price_usd')                    
                            ->has('data.0.fraction')
                            ->missing('data.0.asset');
            });
    }
    /**
     * @depends test_asset_pools_items_index_base
     */
    public function test_asset_pool_items_index_with_assets()
    {        
        $user = $this->getValidAdminUser(); 
        
        $assetPool = AssetPool::where('is_active', 1)->inRandomOrder()->first();

        $response = $this->actingAs($user)->getJson(route('asset-pool-items.index', ['pool_id' => $assetPool->id, 'with_assets' => 1]));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.0.asset_amount')
                            ->has('data.0.asset.name')               
                            ->has('data.0.asset.symbol')               
                            ->has('data.0.asset.logo')               
                            ->has('data.0.asset.price_usd');                            
            });
    }            
    /**
     * @depends test_asset_pools_items_index_base
     */
    public function test_asset_pool_items_store()
    {        
        $user = $this->getValidAdminUser();    
            
        $assetPool = AssetPool::where('is_active', 1)->inRandomOrder()->first();
        $assetPoolItem = AssetPoolItem::inRandomOrder()->first();
        $data = [
            'asset_pool_id' => $assetPool->id,
            'asset_id' => $assetPoolItem->asset_id,            
            'fraction' => 10.11,            
        ];

        $response = $this->actingAs($user)
                         ->postJson(route('asset-pool-items.store'), $data);  
                         
        $json = $response->decodeResponseJson();   

        self::$createdItemId = $json['data']['id'];

        // dd($this->createdItemId);

        $response
            ->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.asset_pool_id')
                            ->has('data.asset_amount')               
                            ->has('data.asset_id')                                           
                            ->has('data.price_usd')               
                            ->has('data.pos')               
                            ->where('data.fraction', 10.11)
                            ->etc();
            });

        $this->assertNotEmpty(AssetPoolItem::where('asset_pool_id', $assetPool->id)->where('asset_id', $assetPoolItem->asset_id)->first());
    }  
    /**
     * @depends test_asset_pool_items_store
     */
    public function test_asset_pool_items_update()
    {        
        $user = $this->getValidAdminUser();    
            
        // $assetPool = AssetPool::where('is_active', 1)->inRandomOrder()->first();                
        $assetPoolItem = AssetPoolItem::findOrFail(self::$createdItemId);
        
        $data = [         
            'asset_pool_id' => $assetPoolItem->asset_pool_id,   
            'asset_id' => $assetPoolItem->asset_id,            
            'fraction' => 11.12,            
        ];

        $response = $this->actingAs($user)
                         ->putJson(route('asset-pool-items.update', ['asset_pool_item' => $assetPoolItem->id]), $data);
        
        $response
                ->assertStatus(200)
                ->assertJson(function (AssertableJson $json) use ($assetPoolItem) {
                    return $json->has('data.asset_amount')                                                                                
                                ->has('data.price_usd')               
                                ->has('data.pos')               
                                ->where('data.asset_pool_id', $assetPoolItem->asset_pool_id)
                                ->where('data.asset_id', $assetPoolItem->asset_id)
                                ->where('data.fraction', 11.12)
                                ->etc();
                });   
                
        $this->assertTrue(AssetPoolItem::where('asset_pool_id', $assetPoolItem->asset_pool_id)->where('asset_id', $assetPoolItem->asset_id)->value('fraction') == 11.12); 
    }     
    /**
     * @depends test_asset_pool_items_update
     */
    public function etest_asset_pool_items_delete()
    {        
        $user = $this->getValidAdminUser();    
                          
        $assetPoolItem = AssetPoolItem::findOrFail(self::$createdItemId);

        $response = $this->actingAs($user)
                         ->deleteJson(route('asset-pool-items.destroy', ['asset_pool_item' => $assetPoolItem->id]));
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id', $assetPoolItem->id);            
        
        $this->assertNull(AssetPoolItem::where('id', $assetPoolItem->id)->first());
    }    
}