<?php

namespace Tests\Feature\Api;

use App\Models\ExchangeBotLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;

class ExchangeBotLogTest extends TestCase
{
    use UserTrait;

    public function test_exchange_bot_log_index_base()
    {
        //$user = $this->getValidUser();        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('exchange-bot-log.index'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.exchange_id')
                                ->has('data.0.operation_type')                    
                                ->has('data.0.user_id') 
                                ->whereType('data.0.asset_pool_name', 'null')                   
                                ->has('data.1.exchange_id')
                                ->has('data.1.operation_type')                    
                                ->has('data.1.user_id')
                                ->whereType('data.1.asset_pool_name', 'null');
                }                
            );
    }
    /**
     * @depends test_exchange_bot_log_index_base
     */
    public function test_exchange_bot_log_index_with_items()
    {        
        $user = $this->getValidAdminUser(); 
        
        $response = $this->actingAs($user)->getJson(route('exchange-bot-log.index', ['with_items' => 1, 'offset' => 2, 'count' => 3])); 
        
        // $json = $response->decodeResponseJson();
        // dd($json);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data', 3)
                            ->has('data.0.exchange_id')                            
                            ->whereType('data.0.asset_pool_name', 'string')
                            ->has('data.1.exchange_id')
                            ->whereType('data.1.asset_pool_name', 'string');
            });
    }   
    public function test_exchange_bot_log_count()
    {
        $user = $this->getValidAdminUser();  
        
        $count = ExchangeBotLog::count();                          

        $response = $this->actingAs($user)->getJson(route('exchange-bot-log.count'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($count) {
                    return $json->where('data.count', $count);
                }                
            );
    }
}