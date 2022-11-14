<?php

namespace Tests\Feature\Api;

use Facade\FlareClient\Concerns\UsesTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\AssetPool;
use App\Models\AssetPoolTemplate;

use Tests\Services\UserTrait;

class AssetPoolsTest extends TestCase
{
    use UserTrait;

    public function test_asset_pools_index_base()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('asset-pools.index'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.0.name')
                            ->has('data.0.asset_pool_group_id')               
                            ->has('data.0.price_usd')                    
                            ->has('data.0.group_name');
            });
    }
    /**
     * @depends test_asset_pools_index_base
     */
    public function test_asset_pools_index_with_items()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('asset-pools.index', ['with_items' => 1]));

        // $json = $response->decodeResponseJson();
        // dd($json['data'][0]);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.0.name')
                            ->has('data.0.assets.0.asset_pool_id')               
                            ->has('data.0.assets.0.asset_amount')               
                            ->has('data.0.assets.0.fraction')               
                            ->has('data.0.assets.0.asset_name')               
                            ->has('data.0.assets.0.logo')               
                            ->has('data.0.assets.0.pool_item_price_usd');                            
            });
    }
    /**
     * @depends test_asset_pools_index_base
     */
    public function test_asset_pools_index_is_active_0()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('asset-pools.index', ['is_active' => 0]));

        $json = $response->decodeResponseJson();        

        $data = collect($json['data']);                         
        $this->assertTrue($data->where('is_active', 0)->count() > 0);
        $this->assertTrue($data->where('is_active', 1)->count() == 0);

        $response->assertStatus(200);            
    }
    /**
     * @depends test_asset_pools_index_base
     */
    public function test_asset_pools_index_is_active_1()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('asset-pools.index', ['is_active' => 1]));

        $json = $response->decodeResponseJson();        

        $data = collect($json['data']);                         
        $this->assertTrue($data->where('is_active', 1)->count() > 0);
        $this->assertTrue($data->where('is_active', 0)->count() == 0);

        $response->assertStatus(200);            
    }
    /**
     * @depends test_asset_pools_index_base
     */
    public function test_asset_pools_index_is_active_minus1()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('asset-pools.index', ['is_active' => -1]));

        $json = $response->decodeResponseJson();        

        $data = collect($json['data']);                         
        $this->assertTrue($data->where('is_active', 1)->count() > 0);
        $this->assertTrue($data->where('is_active', 0)->count() > 0);

        $response->assertStatus(200);            
    }    
    /**
     * @depends test_asset_pools_index_base
     */
    public function test_asset_pools_show()
    {        
        $user = $this->getValidAdminUser(); 
        
        $assetPool = AssetPool::inRandomOrder()->first();

        $response = $this->actingAs($user)->getJson(route('asset-pools.show', ['asset_pool' => $assetPool->id]));
        
        // $json = $response->decodeResponseJson();        
        // dd($json);
        
        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.name')
                            ->has('data.price_usd')               
                            ->has('data.group_name')               
                            ->has('data.is_active')
                            ->missing('data.assets');                            
            });
    }
    /**
     * @depends test_asset_pools_show
     */
    public function test_asset_pools_show_with_items()
    {        
        $user = $this->getValidAdminUser(); 
        
        $assetPool = AssetPool::where('is_active', 1)->inRandomOrder()->first();

        $response = $this->actingAs($user)->getJson(route('asset-pools.show', ['asset_pool' => $assetPool->id, 'with_items' => 1]));        
        
        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.name')
                            ->has('data.price_usd')               
                            ->has('data.group_name')               
                            ->has('data.assets.0.asset_pool_id')
                            ->has('data.assets.0.fraction')
                            ->has('data.assets.0.asset_name')
                            ->has('data.assets.1.asset_pool_id')
                            ->has('data.assets.1.fraction')
                            ->has('data.assets.1.asset_name');
            });
    }
    /**
     * @depends test_asset_pools_show
     */
    public function test_asset_pools_store()
    {        
        $user = $this->getValidAdminUser();    
            
        $data = AssetPool::where('is_active', 1)->inRandomOrder()->first()->toArray();
        unset(
            $data['id'],
            $data['pos'],
            $data['logo'],
            $data['created_at'],
            $data['rebalanced_at'],
            $data['updated_at'],
        );        
        $data['name'] = 'XXX-test-XXX';
        $data['name_short'] = 'XXX-test-X';
        $data['description'] = 'XXX-test-DESC';
        $data['is_active'] = 1;
        $data['asset_pool_template_id'] = 0;

        $response = $this->actingAs($user)
                         ->postJson(route('asset-pools.store'), $data);
        
        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'XXX-test-XXX')
            ->assertJsonPath('data.name_short', 'XXX-test-X')
            ->assertJsonPath('data.description', 'XXX-test-DESC')
            ->assertJsonPath('data.is_active', 1);

        $this->assertNotEmpty(AssetPool::where('name', 'XXX-test-XXX')->first());
    }  
    /**
     * @depends test_asset_pools_store
     */
    public function test_asset_pools_update()
    {        
        $user = $this->getValidAdminUser();    
            
        $assetPool = AssetPool::where('name', 'XXX-test-XXX')->firstOrFail();

        $data = [];        
        $data['is_new_asset_action'] = 1;        
        $data['name'] = 'XXX-test-XXX-1';
        $data['name_short'] = 'XXX-test-1';        
        $data['is_active'] = 1;        

        $response = $this->actingAs($user)
                         ->putJson(route('asset-pools.update', ['asset_pool' => $assetPool->id]), $data);
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'XXX-test-XXX-1')
            ->assertJsonPath('data.name_short', 'XXX-test-1')
            ->assertJsonPath('data.is_active', 1);

        $this->assertNotEmpty(AssetPool::where('name', 'XXX-test-XXX-1')->first());
    }     
    /**
     * @depends test_asset_pools_update
     */
    public function test_asset_pools_delete()
    {        
        $user = $this->getValidAdminUser();    
            
        $assetPool = AssetPool::where('name', 'XXX-test-XXX-1')->firstOrFail();

        $response = $this->actingAs($user)
                         ->deleteJson(route('asset-pools.destroy', ['asset_pool' => $assetPool->id]));
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'XXX-test-XXX-1');            
        
        $this->assertNull(AssetPool::where('name', 'XXX-test-XXX-1')->first());
    } 
    /**
     * @depends test_asset_pools_update
     */
    public function test_asset_pools_update_topmarketcap()
    {        
        $user = $this->getValidAdminUser();    
            
        $assetPool = AssetPool::where('is_topmarketcap_based', 0)->where('asset_pool_template_id', '>', 0)->inRandomOrder()->firstOrFail();

        // dd($assetPool->toArray());

        $data = [];                
        $data['is_topmarketcap_based'] = 1;        
        $data['is_active'] = 1;        

        $response = $this->actingAs($user)
                         ->putJson(route('asset-pools.update', ['asset_pool' => $assetPool->id]), $data);
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', $assetPool->name);        
    }
    /**
     * @depends test_asset_pools_update_topmarketcap
     */
    public function test_asset_pools_update_template()
    {        
        $user = $this->getValidAdminUser();    
            
        $assetPool = AssetPool::where('is_topmarketcap_based', 0)->where('asset_pool_template_id', '>', 0)->inRandomOrder()->firstOrFail();
        $assetPoolTemplate = AssetPoolTemplate::where('id', '<>', $assetPool->asset_pool_template_id)->inRandomOrder()->firstOrFail();

        // dd($assetPool->toArray(), $assetPoolTemplate->toArray());

        $data = [];                
        $data['asset_pool_template_id'] = $assetPoolTemplate->id;        
        $data['is_active'] = 1;        

        $response = $this->actingAs($user)
                         ->putJson(route('asset-pools.update', ['asset_pool' => $assetPool->id]), $data);
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', $assetPool->name)        
            ->assertJsonPath('data.asset_pool_template_id', $assetPoolTemplate->id);        
    }
    /**
     * @depends test_asset_pools_update
     */
    public function test_asset_pools_restart()
    {        
        $user = $this->getValidAdminUser();    
            
        $assetPool = AssetPool::inRandomOrder()->firstOrFail();

        $response = $this->actingAs($user)
                         ->postJson(route('asset-pools.restart', ['assetPool' => $assetPool->id]));
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', $assetPool->name)        
            ->assertJsonPath('data.price_usd', 100);
    }
}