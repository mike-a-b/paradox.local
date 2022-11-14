<?php

namespace App\Services;
use App\Services\CoinmarketcapApi;
use App\Models\Asset;
use App\Models\AssetPoolTemplate;
use App\Models\AssetPool;
use App\Models\AssetPoolItem;
use App\Models\UserAssetPool;
use App\Models\UserAssetPoolItem;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Boolean;

class CoinmarketcapCategory
{    
    public function rebuildAssetPool(AssetPool $assetPool, string $sourceType) {

        $assets = new Asset();
        $cmc = new CoinmarketcapApi();        
        $assetPoolItem = new AssetPoolItem();
        $assetPoolTemplate = new AssetPoolTemplate();
        
        $assetPoolTpl = $assetPoolTemplate::find($assetPool->asset_pool_template_id);

        if (!$assetPoolTpl) {
            return false;
        }

        $assetPoolTplBody = json_decode($assetPoolTpl->body);         

        //dd($assetPoolTpl->toArray());
        //dd($assetPoolMetaId);

        //dd($sourceType);
        
        if ($sourceType === 'cmk_category') {
            $assetPoolMeta = json_decode($assetPool->meta);        
            $assetPoolMetaId = $assetPoolMeta->id;
            $coinsList = $cmc->getCategory($assetPoolMetaId);
            if (empty($coinsList->coins)) {
                sleep(1);
                $coinsList = $cmc->getCategory($assetPoolMetaId);
            }
            $coinsList = empty($coinsList->coins) ? [] : $coinsList->coins;
        } elseif ($sourceType === 'top_cmk_category') {            
            $list = $assets::where('cmc_rank', '>', 0)->offset(0)->limit(100)->orderBy('cmc_rank', 'ASC')->get();
            $coinsList = [];
            foreach ($list as $li) {
                $coinsList[] = $li;
            }
            //dd($coinsList->toArray());
        }     
        // dd($coinsList);

        if (!empty($coinsList)) {                
            //$coinsArr = $coinsList->coins;
            
            $assetPoolItem->where('asset_pool_id', $assetPool->id)->delete();

            foreach ($assetPoolTplBody as $tplAsset) {
                while (true) {
                    $coin = array_shift($coinsList);                
                    if ($coin) {
                        $asset = $assets->where('is_stoplisted', 0)
                                        ->where('symbol', $coin->symbol)
                                        ->where('name', $coin->name)
                                        ->first();                                                         
                        if ($asset) {
                            $assetPoolItem->commit([
                                'asset_pool_id' => $assetPool->id,
                                'asset_id' => $asset->id,
                                'fraction' => $tplAsset->fraction,                                                                                 
                            ]);
                            break;
                        }
                    } else {
                        break;
                    }
                }
            }

            $assetPool->rebalanced_at = Carbon::now();
            $assetPool->save();
        }

        return true;
    }    

    public function rebuildUserAssetPools(int $assetPoolId) {
        $userAssetPoolItemTbl = new UserAssetPoolItem();        
        $chunkLen = 20;
        UserAssetPool::where('asset_pool_id', $assetPoolId)
            ->chunkById($chunkLen, function ($userAssetPoolList) use ($userAssetPoolItemTbl) {
                //dd($userAssetPoolList->toArray());
                foreach ($userAssetPoolList as $userAssetPool) {
                    $userAssetPoolItems = UserAssetPoolItem::where('user_asset_pool_id', $userAssetPool->id)->get();                         
                    foreach ($userAssetPoolItems as $userAssetPoolItem) {
                        //$assetPoolItem = AssetPoolItem::find($userAssetPoolItem->asset_pool_item_id);
                        $userAssetPoolItem->delete();                        
                        // DELETE LOG
                    }
                    
                    $userAssetPoolItemTbl->initAllByUserPoolId($userAssetPool);                    

                    $userAssetPool->price_usd = UserAssetPoolItem::where('user_asset_pool_id', $userAssetPool->id)->sum('price_usd');
                    $userAssetPool->save();                    
                }                
            });        
    }
}
