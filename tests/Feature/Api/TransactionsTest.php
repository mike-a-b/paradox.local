<?php

namespace Tests\Feature\Api;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Services\UserTrait;

class TransactionsTest extends TestCase
{
    use UserTrait;

    public function test_transactions_index_base()
    {
        //$user = $this->getValidUser();        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson(route('transactions.index'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                    return $json->has('data.0.type_id')
                                ->has('data.0.type_name_short')                    
                                ->has('data.0.from_user_id') 
                                ->whereType('data.0.from_user_name', 'null')                   
                                ->has('data.1.type_id')
                                ->has('data.1.type_name_short')                    
                                ->has('data.1.from_user_id')
                                ->whereType('data.1.from_user_name', 'null');
                }                
            );
    }
    /**
     * @depends test_transactions_index_base
     */
    public function test_transactions_index_with_items()
    {        
        $user = $this->getValidAdminUser(); 
        
        $response = $this->actingAs($user)->getJson(route('transactions.index', ['with_items' => 1, 'offset' => 2, 'count' => 3])); 
        
        // $json = $response->decodeResponseJson();
        // dd($json);

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data', 3)
                            ->has('data.0.type_id')                            
                            ->whereType('data.0.from_user_name', 'string')
                            ->has('data.1.type_id')
                            ->whereType('data.1.from_user_name', 'string');
            });
    }   
    public function test_transactions_count()
    {
        $user = $this->getValidAdminUser();  
        
        $count = Transaction::count();                          

        $response = $this->actingAs($user)->getJson(route('transactions.count'));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($count) {
                    return $json->where('data.count', $count);
                }                
            );
    }
}