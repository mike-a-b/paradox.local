<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;

class AssetPoolGroupsTest extends TestCase
{
    use UserTrait;

    public function test_assets_pool_groups_index_base()
    {
        //$user = $this->getValidUser();        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('asset-pool-groups.index'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.name')
                                ->has('data.0.g_type');                    
                }                
            );
    }

    /**
     * @depends test_assets_pool_groups_index_base
     */
    public function test_assets_pool_groups_index_filter() 
    {  
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('asset-pool-groups.index', ['g_type=2']));        

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.0.g_type', 2);    
    }
}