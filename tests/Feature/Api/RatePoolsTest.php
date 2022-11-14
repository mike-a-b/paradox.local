<?php

namespace Tests\Feature\Api;

use Facade\FlareClient\Concerns\UsesTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\RatePool;
use Carbon\Carbon;
use Tests\Services\UserTrait;

class RatePoolsTest extends TestCase
{
    use UserTrait;

    public function test_rate_pools_index_base()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('rate-pools.index'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.0.name')
                            ->has('data.0.asset_pool_group_id')               
                            ->has('data.0.rate')                    
                            ->has('data.0.currency_id')                    
                            ->has('data.0.date_start')                    
                            ->has('data.0.date_end')                    
                            ->has('data.0.group_name')
                            ->has('data.0.is_active')
                            ->missing('data.0.assets');
            });
    }
    /**
     * @depends test_rate_pools_index_base
     */
    // public function test_rate_pools_index_with_items()
    // {        
    //     $user = $this->getValidAdminUser();        

    //     $response = $this->actingAs($user)->getJson(route('rate-pools.index', ['with_items' => 1]));

    //     // $json = $response->decodeResponseJson();
    //     // dd($json['data'][0]);

    //     $response
    //         ->assertStatus(200)
    //         ->assertJson(function (AssertableJson $json) {
    //             return $json->has('data.0.name')
    //                         ->has('data.0.assets.0.asset_pool_id')               
    //                         ->has('data.0.assets.0.asset_amount')               
    //                         ->has('data.0.assets.0.fraction')               
    //                         ->has('data.0.assets.0.asset_name')               
    //                         ->has('data.0.assets.0.logo')               
    //                         ->has('data.0.assets.0.pool_item_price_usd');                            
    //         });
    // }
    /**
     * @depends test_rate_pools_index_base
     */
    // public function test_rate_pools_index_is_active_0()
    // {        
    //     $user = $this->getValidAdminUser();        

    //     $response = $this->actingAs($user)->getJson(route('rate-pools.index', ['is_active' => 0]));

    //     $json = $response->decodeResponseJson();        

    //     $data = collect($json['data']);                         
    //     $this->assertTrue($data->where('is_active', 0)->count() > 0);
    //     $this->assertTrue($data->where('is_active', 1)->count() == 0);

    //     $response->assertStatus(200);            
    // }
    /**
     * @depends test_rate_pools_index_base
     */
    // public function test_rate_pools_index_is_active_1()
    // {        
    //     $user = $this->getValidAdminUser();        

    //     $response = $this->actingAs($user)->getJson(route('rate-pools.index', ['is_active' => 1]));

    //     $json = $response->decodeResponseJson();        

    //     $data = collect($json['data']);                         
    //     $this->assertTrue($data->where('is_active', 1)->count() > 0);
    //     $this->assertTrue($data->where('is_active', 0)->count() == 0);

    //     $response->assertStatus(200);            
    // }
    /**
     * @depends test_rate_pools_index_base
     */
    // public function test_rate_pools_index_is_active_minus1()
    // {        
    //     $user = $this->getValidAdminUser();        

    //     $response = $this->actingAs($user)->getJson(route('rate-pools.index', ['is_active' => -1]));

    //     $json = $response->decodeResponseJson();        

    //     $data = collect($json['data']);                         
    //     $this->assertTrue($data->where('is_active', 1)->count() > 0);
    //     $this->assertTrue($data->where('is_active', 0)->count() > 0);

    //     $response->assertStatus(200);            
    // }    
    /**
     * @depends test_rate_pools_index_base
     */
    public function test_rate_pools_show()
    {        
        $user = $this->getValidAdminUser(); 
        
        $ratePool = RatePool::inRandomOrder()->first();

        $response = $this->actingAs($user)->getJson(route('rate-pools.show', ['rate_pool' => $ratePool->id]));
        
        // $json = $response->decodeResponseJson();        
        // dd($json);
        
        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.name')
                            ->has('data.asset_pool_group_id')               
                            ->has('data.rate')                    
                            ->has('data.currency_id')                    
                            ->has('data.date_start')                    
                            ->has('data.date_end')                    
                            ->has('data.group_name')
                            ->has('data.is_active');                            
            });
    }
    /**
     * @depends test_rate_pools_show
     */
    // public function test_rate_pools_show_with_items()
    // {        
    //     $user = $this->getValidAdminUser(); 
        
    //     $assetPool = AssetPool::where('is_active', 1)->inRandomOrder()->first();

    //     $response = $this->actingAs($user)->getJson(route('rate-pools.show', ['asset_pool' => $assetPool->id, 'with_items' => 1]));        
        
    //     $response
    //         ->assertStatus(200)
    //         ->assertJson(function (AssertableJson $json) {
    //             return $json->has('data.name')
    //                         ->has('data.price_usd')               
    //                         ->has('data.group_name')               
    //                         ->has('data.assets.0.asset_pool_id')
    //                         ->has('data.assets.0.fraction')
    //                         ->has('data.assets.0.asset_name')
    //                         ->has('data.assets.1.asset_pool_id')
    //                         ->has('data.assets.1.fraction')
    //                         ->has('data.assets.1.asset_name');
    //         });
    // }
    /**
     * @depends test_rate_pools_show
     */
    public function test_rate_pools_store()
    {        
        $user = $this->getValidAdminUser();    
            
        $data = RatePool::where('is_active', 1)->inRandomOrder()->first()->toArray();
        
        $data = [];
        $data['name'] = 'XXX-test-XXX';
        $data['description'] = 'XXX-test-DESC';
        $data['description_ru'] = 'XXX-test-DESC-ru';
        $data['currency_id'] = 1;
        $data['rate'] = 4.4;
        // $data['date_start'] = Carbon::now()->format('Y-m-d');
        // $data['date_end'] = Carbon::now()->addDays(10)->format('Y-m-d');
        $data['is_active'] = 1;
        $data['asset_pool_group_id'] = 10;

        $response = $this->actingAs($user)
                         ->postJson(route('rate-pools.store'), $data);
        
        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', $data['name'])            
            ->assertJsonPath('data.description', $data['description'])
            ->assertJsonPath('data.currency_id', $data['currency_id'])
            ->assertJsonPath('data.rate', $data['rate'])
            // ->assertJsonPath('data.date_start', $data['date_start'])
            // ->assertJsonPath('data.date_end', $data['date_end'])
            ->assertJsonPath('data.asset_pool_group_id', $data['asset_pool_group_id'])
            ->assertJsonPath('data.is_active', $data['is_active']);

        $this->assertNotEmpty(RatePool::where('name', $data['name'])->first());
    }  
    /**
     * @depends test_rate_pools_store
     */
    public function test_rate_pools_update()
    {        
        $user = $this->getValidAdminUser();    
            
        $ratePool = RatePool::where('name', 'XXX-test-XXX')->firstOrFail();

        $data = [];                
        $data['name'] = 'XXX-test-XXX-1';
        $data['description'] = 'XXX-test-DESC-1';
        $data['currency_id'] = 2;
        $data['rate'] = 5.4;
        $data['is_active'] = 0;
        $data['asset_pool_group_id'] = 11;        

        $response = $this->actingAs($user)
                         ->putJson(route('rate-pools.update', ['rate_pool' => $ratePool->id]), $data);
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', $data['name'])            
            ->assertJsonPath('data.description', $data['description'])
            ->assertJsonPath('data.currency_id', $data['currency_id'])
            ->assertJsonPath('data.rate', $data['rate'])
            ->assertJsonPath('data.asset_pool_group_id', $data['asset_pool_group_id'])
            ->assertJsonPath('data.is_active', $data['is_active']);

        $this->assertNotEmpty(RatePool::where('name', $data['name'])->first());
    }     
    /**
     * @depends test_rate_pools_update
     */
    public function test_rate_pools_delete()
    {        
        $user = $this->getValidAdminUser();    
            
        $ratePool = RatePool::where('name', 'XXX-test-XXX-1')->firstOrFail();

        $response = $this->actingAs($user)
                         ->deleteJson(route('rate-pools.destroy', ['rate_pool' => $ratePool->id]));
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'XXX-test-XXX-1');            
        
        $this->assertNull(RatePool::where('name', 'XXX-test-XXX-1')->first());
    }    
}