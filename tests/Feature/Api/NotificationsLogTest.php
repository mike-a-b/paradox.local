<?php

namespace Tests\Feature\Api;

use Facade\FlareClient\Concerns\UsesTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\NotificationsLog;

use Tests\Services\UserTrait;

class NotificationsLogTest extends TestCase
{
    use UserTrait;

    public function test_notifications_log_index_base()
    {
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson('/api/v1/notifications-log');

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.0.id')
                            ->has('data.0.code')
                            ->has('data.0.title');
            }                
        );
    }
    /**
     * @depends test_notifications_log_index_base
     */
    public function test_notifications_log_index_limits()
    {        
        $user = $this->getValidAdminUser();       
        
        $item = NotificationsLog::firstOrFail();
        $item->is_checked = 1;
        $item->save();

        $response = $this->actingAs($user)->getJson('/api/v1/notifications-log?is_checked=1&offset=0&count=3');        

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.0.is_checked', 1)
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data', 3);
            });    
    }    
    /**
     * @depends test_notifications_log_index_base
     */
    public function test_notifications_log_count()
    {        
        $user = $this->getValidAdminUser();        

        $response = $this->actingAs($user)->getJson('/api/v1/notifications-log/count');
        
        $response
            ->assertStatus(200)            
            ->assertJson(function (AssertableJson $json) {
                return $json->has('data.count');
            });
    }    
    /**
     * @depends test_notifications_log_index_base
     */
    public function test_notifications_log_update()
    {        
        $user = $this->getValidAdminUser();    
            
        $itemPrev = NotificationsLog::firstOrFail();

        $isCheckedPrev = $itemPrev->is_checked;       
        $isChecked = !$isCheckedPrev ? 1 : 0;

        $data = ['is_checked' => $isChecked];
        $response = $this->actingAs($user)
                         ->putJson("/api/v1/notifications-log/{$itemPrev->id}", $data);
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id', $itemPrev->id)
            ->assertJsonPath('data.is_checked', $isChecked);
        
        $item = NotificationsLog::findOrFail($itemPrev->id);

        $this->assertEquals($item->is_checked, $isChecked);
        $item->is_checked = $isCheckedPrev;
        $item->save();
    }           
    /**
     * @depends test_notifications_log_index_base
     */
    public function test_notifications_log_check_all()
    {        
        $user = $this->getValidAdminUser();    
            
        $itemPrev = NotificationsLog::firstOrFail();

        $isCheckedPrev = $itemPrev->is_checked;       
        $isChecked = $isCheckedPrev ? 0 : 1;

        $countPrev = NotificationsLog::where('is_checked', $isCheckedPrev)->count();          

        $data = ['is_checked' => $isChecked];
        //dd($data);
        $response = $this->actingAs($user)
                         ->postJson("/api/v1/notifications-log/check-all", $data);                               
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.count', $countPrev);
        
        $countAll = NotificationsLog::count(); 
        $countChecked = NotificationsLog::where('is_checked', $isChecked)->count();

        $this->assertEquals($countAll, $countChecked);
    }
    /**
     * @depends test_notifications_log_check_all
     */
    public function test_notifications_log_check_all_empty()
    {        
        $user = $this->getValidAdminUser();    
                    
        $itemPrev = NotificationsLog::firstOrFail();
        $itemPrev->is_checked = 0;
        $itemPrev->save();
        $countNotChecked = NotificationsLog::where('is_checked', 0)->count();

        $response = $this->actingAs($user)
                         ->postJson("/api/v1/notifications-log/check-all");                                   
        
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.count', $countNotChecked);
        
        $countAll = NotificationsLog::count(); 
        $countChecked = NotificationsLog::where('is_checked', 1)->count();

        $this->assertEquals($countAll, $countChecked);
    }           
}