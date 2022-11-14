<?php

namespace Tests\Feature\Api;

use Facade\FlareClient\Concerns\UsesTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Asset;
use App\Models\AssetPool;
use App\Models\AssetPoolItem;
use App\Models\UserAssetPoolItem;
use App\Models\Commission;
use App\Services\UserPoolOperationsService;

use Tests\Services\UserTrait;

class AssetPoolCalcsTest extends TestCase
{
    use UserTrait;

    static protected $poolId;        
    
    public function test_asset_pool_store()
    {        
        $user = $this->getValidAdminUser();            

        $data = [];   
        $data['name'] = 'CALCS-test-'.rand(1000, 9999);        
        $data['name_short'] = $data['name'];
        $data['description'] = 'XXX-test-DESC';
        $data['description_ru'] = 'XXX-test-DESC-ru';
        $data['is_active'] = 1;
        $data['asset_type_id'] = 1;
        $data['asset_pool_group_id'] = 21;
        $data['is_topmarketcap_based'] = 1;
        $data['asset_pool_template_id'] = 1;

        $response = $this->actingAs($user)
                         ->postJson(route('asset-pools.store'), $data);

        $json = $response->decodeResponseJson();
        self::$poolId = $json['data']['id'];
        
        // echo self::$poolId;

        $response->assertStatus(201);        
    }  
    /**
     * @depends test_asset_pool_store
     */
    public function test_asset_pool_update()
    {        
        $user = $this->getValidAdminUser();    
            
        $assetPool = AssetPool::findOrFail(self::$poolId);

        $assetPool->price_start_usd = 100;
        // $assetPool->price_usd = $assetPool->price_start_usd;
        $assetPool->save();

        $data = [];        
        $data['is_new_asset_action'] = 1;        
        $data['asset_pool_template_id'] = 34;        
        $data['is_active'] = 1;        
        $data['price_usd'] = 100;        

        $response = $this->actingAs($user)
                         ->putJson(route('asset-pools.update', ['asset_pool' => $assetPool->id]), $data);
        
        $response->assertStatus(200);        
    }        
    /**
     * @depends test_asset_pool_update
     */
    public function test_asset_pool_clculate_items_amount()
    {                    
        // $assetPool = AssetPool::findOrFail(self::$poolId);
        $assetPoolItems = AssetPoolItem::where('asset_pool_id', self::$poolId)->get();

        $price = 0;
        foreach($assetPoolItems as $item) {
            $price += $item->price_usd;
        }
        $this->assertEquals(100, $price);

        $price = 0;
        foreach($assetPoolItems as $item) {
            $asset = Asset::findOrFail($item->asset_id);
            $price += $asset->price_usd*$item->asset_amount;
            //$price += bcmul($asset->price_usd, $item->asset_amount, $BC_BASE);
        }
        //echo 100, ' ', $price;
        $this->assertTrue(100 - $price < pow(10, -11));
    } 
    /**
     * @depends test_asset_pool_clculate_items_amount
     */
    public function test_asset_pool_user_buy_and_clculate()
    {            
        $BC_BASE = config('app.bc_base');        

        $priceStartUsd = 987624;
        $commission = Commission::where('type_id', Commission::TYPE_ID_ASSET_POOL_BUY)->firstOrFail()->value/100;
        $priceWithCommUsd = bcsub($priceStartUsd, bcmul($priceStartUsd, $commission, $BC_BASE), $BC_BASE);

        $user = $this->getValidUser(); 
        $userProfile = $user->profile;
        $userProfile->balance_usdt = 1000000;
        $userProfile->save();        

        $params = [
            'price_start_usd' => $priceStartUsd,       
            'asset_pool_id' => self::$poolId,       
        ];        
        $responseData = (new UserPoolOperationsService)->createAssetPool($params, $user);        
        $userAssetPoolId = $responseData['user_updates_data']['user_asset_pool_history']->user_asset_pool_id;
        //dd($userAssetPoolId);

        $assetPoolItems = UserAssetPoolItem::where('user_asset_pool_id', $userAssetPoolId)->get();

        $price = 0;
        foreach($assetPoolItems as $item) {
            $price = bcadd($item->price_usd, $price, $BC_BASE);
        }
        //echo $priceWithCommUsd, ' ', $price;
        $this->assertEquals($priceWithCommUsd, $price);
        
        $price = 0;
        foreach($assetPoolItems as $item) {
            //dd($item);
            $assetPoolItem = AssetPoolItem::findOrFail($item->asset_pool_item_id);
            $asset = Asset::findOrFail($assetPoolItem->asset_id);
            $price = bcadd(bcmul($item->asset_amount, $asset->price_usd, $BC_BASE), $price, $BC_BASE);
        }
        $price = round($price, 4);
        $priceWithCommUsd = round($priceWithCommUsd, 4);
        //echo $priceWithCommUsd, ' ', $price;
        $this->assertEquals($priceWithCommUsd, $price);
    }
}