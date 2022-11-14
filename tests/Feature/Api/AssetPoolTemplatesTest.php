<?php

namespace Tests\Feature\Api;

use Facade\FlareClient\Concerns\UsesTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\AssetPoolTemplate;

use Tests\Services\UserTrait;

class AssetPoolTemplatesTest extends TestCase
{
    use UserTrait;    

    public function test_asset_pool_templates_index()
    {        
        $user = $this->getValidAdminUser();

        $response = $this->actingAs($user)->getJson(route('asset-pool-templates.index'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.0.name')
                            ->has('data.0.is_active')               
                            ->has('data.0.asset_count')
                            ->has('data.0.body');
            });
    }    
    /**
     * @depends test_asset_pool_templates_index
     */
    public function test_asset_pool_templates_show()
    {        
        $user = $this->getValidAdminUser();    
                          
        $assetPoolTemplate = AssetPoolTemplate::first();

        $response = $this->actingAs($user)
                         ->getJson(route('asset-pool-templates.show', ['asset_pool_template' => $assetPoolTemplate->id]));
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id', $assetPoolTemplate->id)            
            ->assertJsonPath('data.name', $assetPoolTemplate->name);            
    }    
    /**
     * @depends test_asset_pool_templates_show
     */
    public function test_asset_pool_templates_update()
    {        
        $user = $this->getValidAdminUser();    
            
        $assetPoolTemplate = AssetPoolTemplate::first();
        
        $body = json_decode($assetPoolTemplate->body);
        $body[0]->fraction = 22.33;
        $bodyJson = json_encode($body);

        $updateData = [         
            'name' => $assetPoolTemplate->name . '123',   
            'asset_count' => $assetPoolTemplate->asset_count + 1,            
            'is_active' => $assetPoolTemplate->is_active ? 0 : 1,            
            'body' => $bodyJson,            
        ];

        $response = $this->actingAs($user)
                         ->putJson(route('asset-pool-templates.update', ['asset_pool_template' => $assetPoolTemplate->id]), $updateData);
        
        $response
                ->assertStatus(200)
                ->assertJson(function (AssertableJson $json) use ($updateData) {
                    return $json->where('data.name', $updateData['name'])
                                ->where('data.asset_count', $updateData['asset_count'])
                                ->where('data.is_active', $updateData['is_active'])
                                ->etc();
                });   
                
        $assetPoolTemplate = AssetPoolTemplate::first();
        $this->assertEquals($assetPoolTemplate->name, $updateData['name']); 
        $this->assertEquals($assetPoolTemplate->asset_count, $updateData['asset_count']); 
        $this->assertEquals($assetPoolTemplate->is_active, $updateData['is_active']); 
        $this->assertEquals($assetPoolTemplate->body, $updateData['body']); 
    }         
}