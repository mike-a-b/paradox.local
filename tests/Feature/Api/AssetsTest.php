<?php

namespace Tests\Feature\Api;

use Facade\FlareClient\Concerns\UsesTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Asset;

use Tests\Services\UserTrait;

class AssetsTest extends TestCase
{
    use UserTrait;

    public function test_assets_index_base()
    {
        //$user = $this->getValidUser();        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('assets.index'));
        //$response = $this->actingAs($user)->get(route('home'));

        //$json = $response->decodeResponseJson();

        //dd($json);

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Bitcoin')
            ->assertJsonPath('data.0.symbol', 'BTC');
            // ->assertJson([
            //     'created' => true,
            // ]);
    }
    /**
     * @depends test_assets_index_base
     */
    public function test_assets_index_search()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('assets.index', ['query=Litecoin']));        

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Litecoin')
            ->assertJsonPath('data.0.symbol', 'LTC');            
    }
    /**
     * @depends test_assets_index_base
     */
    public function test_assets_index_limits()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('assets.index', ['offset=0', 'count=10']));
        
        $json = $response->decodeResponseJson();

        //dd(count($json['data']));

        $response->assertStatus(200);
        
        $this->assertTrue(count($json['data']) === 10);
    }
    /**
     * @depends test_assets_index_base
     */
    public function test_assets_show()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('assets.show', ['asset'=>1]));
        // $response = $this->actingAs($user)->getJson('/api/v1/assets/1');
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'Bitcoin')
            ->assertJsonPath('data.symbol', 'BTC');
    }
    /**
     * @depends test_assets_index_base
     */
    public function test_assets_store()
    {        
        $user = $this->getValidAdminUser();    
            
        $data = Asset::first()->toArray();
        unset(
            $data['id'],
            $data['pos'],
            $data['created_at'],
            $data['updated_at'],
        );
        $data['coinmarketcap_id'] = 100500;
        $data['name'] = 'XXX-test-XXX';
        $data['symbol'] = 'XXX-test';
        $data['slug'] = 'xxx-test';        

        $response = $this->actingAs($user)
                         ->postJson(route('assets.store'), $data);
        
        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'XXX-test-XXX')
            ->assertJsonPath('data.symbol', 'XXX-test')
            ->assertJsonPath('data.slug', 'xxx-test');
    }  
    /**
     * @depends test_assets_store
     */
    public function test_assets_update()
    {        
        $user = $this->getValidAdminUser();    
            
        $id = Asset::where('coinmarketcap_id', 100500)->value('id');

        $data = [];        
        $data['name'] = 'XXX-test-XXX-1';
        $data['symbol'] = 'XXX-test-1';
        //$data['slug'] = 'xxx-test';        

        $response = $this->actingAs($user)
                         ->putJson(route('assets.update', ['asset'=>$id]), $data);
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'XXX-test-XXX-1')
            ->assertJsonPath('data.symbol', 'XXX-test-1')
            ->assertJsonPath('data.slug', 'xxx-test');
    } 
    /**
     * @depends test_assets_update
     */
    public function test_assets_delete()
    {        
        $user = $this->getValidAdminUser();    
            
        $id = Asset::where('coinmarketcap_id', 100500)->value('id'); 

        $response = $this->actingAs($user)
                         ->deleteJson(route('assets.destroy', ['asset'=>$id]));
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'XXX-test-XXX-1');            

        $id = Asset::where('coinmarketcap_id', 100500)->value('id'); 
        $this->assertNull($id);
    } 
}