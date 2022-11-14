<?php

namespace App\Services;
use App\Services\CoinmarketcapApi;
use App\Services\NotificationsLogService;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\AssetPool;
use App\Models\AssetPoolItem;
use App\Models\AssetPoolHistory;
use App\Models\UserAssetPool;
use App\Models\UserAssetPoolItem;
use App\Models\UserProfile;
use App\Models\UserBalanceHistory;
use App\Models\ExchangeBotLog;

class DataRecalculator
{    
    public function updateAssets() {
        $BC_BASE = config('app.bc_base');        
        $assets = new Asset();
        $cmc = new CoinmarketcapApi();        
        $cmc->setLogService(new NotificationsLogService());

        $assetPoolIds = AssetPool::where('is_active', 1)->get()->pluck('id');
        $assetIds = AssetPoolItem::whereIn('asset_pool_id', $assetPoolIds)->get()->pluck('asset_id');
        $coinmarketcapIds = Asset::whereIn('id', $assetIds)
                                 ->orWhere('id', '<', 100)
                                 ->get()
                                 ->pluck('coinmarketcap_id');
        
        //dd($coinmarketcapIds->toArray());
        
        //dd($coinmarketcapIds->join(','));

        //$coinmarketcapIds = collect([74]);
        
        // $countPack = 100;
        // for ($iPack=0; $iPack < 5; $iPack++) {
            // $list = $cmc->getCryptoCurrencyList(1 + $iPack*$countPack, $countPack);                        
            // $list = $cmc->getCryptoCurrencyList(1, 500);            
            $list = $cmc->getCryptoCurrencyQuotesLatest(['id' => $coinmarketcapIds->join(',')]);
            //dd($list);
            if (!empty($list)) {
                foreach ($list as $li) {
                    // if ($li->symbol == 'USDT') {
                    //     dd($li);
                    // }                    
                    $assetPriceUsd = $li->quote->USD->price ?? 0;
                    $assetMarketcapUsd = $li->quote->USD->market_cap ?? 0;
                    $assetCmcRank = $li->cmc_rank ?? 0;
                    $asset = $assets->where('symbol', $li->symbol)->where('name', $li->name)->first();                
                    if ($assetPriceUsd && $asset) {
                        $assetId = $asset->id;
                        $asset->update([    
                            'price_usd' => $assetPriceUsd,
                            'market_cap' => $assetMarketcapUsd,
                            'cmc_rank' => $assetCmcRank
                        ]);
                        //dd($asset->toArray());
                        AssetHistory::create([
                            'asset_id' => $assetId,
                            'price_usd' => $assetPriceUsd,
                            'market_cap' => $assetMarketcapUsd,
                            'cmc_rank' => $assetCmcRank,
                            'source' => 1
                        ]);
                        $chunkLen = 20;
                        AssetPoolItem::where('asset_id', $assetId)
                            ->chunkById($chunkLen, function ($poolItemsList) use ($assetPriceUsd, $BC_BASE) {
                                foreach ($poolItemsList as $poolItem) {
                                    //dd($poolItem->toArray());
                                    // $poolItem->price_usd = $assetPriceUsd*$poolItem->asset_amount;
                                    $poolItem->price_usd = bcmul(number_format($assetPriceUsd, $BC_BASE, '.', ''), $poolItem->asset_amount, $BC_BASE);
                                    $poolItem->save();                                                
                                }                        
                            });                
                    }
                }
            }            
        //     sleep(1);            
        // }           
    }

    public function updatePoolsHistory($poolId=null) {
        $chunkLen = 20;        
        AssetPool::where('is_active', 1)
                ->where(function($query) use ($poolId) {
                    if ($poolId) {                                    
                        $query->where('id', $poolId);
                    }            
                })
                ->chunkById($chunkLen, function ($poolsList) {                                  
                    foreach ($poolsList as $pool) {                                              
                        $poolFractionSum = AssetPoolItem::where('asset_pool_id', $pool->id)->sum('fraction');                        
                        // echo $pool->id, ' ', $poolFractionSum, "\n";  
                        // exit;
                        if (floatval($poolFractionSum) == 100) {                                                    
                            $pool->price_usd = AssetPoolItem::where('asset_pool_id', $pool->id)->sum('price_usd');
                            //dd($pool->price_usd);    
                            $pool->price_start_usd = floatval($pool->price_start_usd) ? $pool->price_start_usd : $pool->price_usd;                        
                            AssetPoolHistory::create([
                                'asset_pool_id' => $pool->id,
                                'price_usd' => $pool->price_usd
                            ]);
                            //dd($pool);
                            $pool->save();                       
                        }                                      
                    }                                                        
                });
    }

    public function updateUserPools() {
        $BC_BASE = config('app.bc_base'); 
        $chunkLen = 20;
        UserAssetPoolItem::where('id', '>', 0) // is_active=1
            ->chunkById($chunkLen, function ($userAssetPoolItems) use ($BC_BASE) {                
                foreach ($userAssetPoolItems as $userAssetPoolItem) {
                    $assetPoolItem = AssetPoolItem::find($userAssetPoolItem->asset_pool_item_id);
                    // $keff = $assetPoolItem->asset_amount != 0 ? $assetPoolItem->price_usd/$assetPoolItem->asset_amount : 0;
                    $keff = $assetPoolItem->asset_amount != 0 ? bcdiv($assetPoolItem->price_usd, $assetPoolItem->asset_amount, $BC_BASE) : 0;
                    // $userAssetPoolItem->price_usd = $keff*$userAssetPoolItem->asset_amount;
                    $userAssetPoolItem->price_usd = bcmul($keff, $userAssetPoolItem->asset_amount, $BC_BASE);
                    $userAssetPoolItem->save();                    
                }                                                        
            });
            
        $chunkLen = 20;
        UserAssetPool::where('asset_pool_id', '>', 0) // is_active=1
            ->chunkById($chunkLen, function ($userAssetPools) {                
                foreach ($userAssetPools as $userAssetPool) {                    
                    $userAssetPool->price_usd = UserAssetPoolItem::where('user_asset_pool_id', $userAssetPool->id)->sum('price_usd');
                    $userAssetPool->save();                    
                }                                                        
            });
    }

    public function rebuildUserPools(int $assetPoolId) : void
    {
        $chunkLen = 20;
        $userAssetPoolItem = new UserAssetPoolItem();
        UserAssetPool::where('asset_pool_id', $assetPoolId)
            ->chunkById($chunkLen, function ($userAssetPools) use ($userAssetPoolItem) {
                foreach ($userAssetPools as $userAssetPool) { 
                    $userAssetPoolItems = UserAssetPoolItem::where('user_asset_pool_id', $userAssetPool->id)->get();   
                    $updateUserBalance = false;
                    if (!empty($userAssetPoolItems)) {
                        foreach ($userAssetPoolItems as $userAssetPoolIt) {
                            //$assetPoolItem = AssetPoolItem::find($userAssetPoolItem->asset_pool_item_id);
                            $userAssetPoolIt->delete();                        
                            // DELETE LOG
                        }
                    } else {
                        $updateUserBalance = true;
                    }           
                    $this->exchangeBotLog($userAssetPoolItems ? $userAssetPoolItems->all() : [], 'sell', $userAssetPool->user_id);
                    
                    $userAssetPoolItemsInit = $userAssetPoolItem->initAllByUserPoolId($userAssetPool);                                           
                    $this->exchangeBotLog($userAssetPoolItemsInit['diff'], 'buy', $userAssetPool->user_id);
        
                    $userAssetPool->price_usd = UserAssetPoolItem::where('user_asset_pool_id', $userAssetPool->id)->sum('price_usd');
                    $userAssetPool->save();  
                    $userAssetPool->setPoolAmount();
                    
                    if ($updateUserBalance) {
                        $this->updateUserProfile($userAssetPool->user_id);
                    }
                }                                                        
            });        
    }

    public function updateUserProfiles() {            
        $userBalanceHistory = new UserBalanceHistory();
        $chunkLen = 20;        
        UserProfile::where('id', '>', 0) // is_active=1
            ->chunkById($chunkLen, function ($userProfiles) use ($userBalanceHistory) {                
                foreach ($userProfiles as $userProfile) {                                                            
                    $userProfile = $userProfile->updateBalance();
                    $userBalanceHistory->commit($userProfile->toArray());
                    // UserBalanceHistory::create([
                    //     'user_id' => $userProfile->user_id,
                    //     'balance_usd' => $userProfileData['balance_usd'],
                    //     'deposits_usd' => $userProfile->deposits_usd - $userProfile->withdraws_usd
                    // ]);
                }                                                        
            });
    }

    public function updateUserProfile(int $userId) : void
    {               
        $userBalanceHistory = new UserBalanceHistory();

        $userProfile = UserProfile::where('user_id', $userId)->first();
        if ($userProfile) {
            $userProfile = $userProfile->updateBalance();
            $userBalanceHistory->commit($userProfile->toArray());
            // UserBalanceHistory::create([
            //     'user_id' => $userProfile->user_id,
            //     'balance_usd' => $userProfileData['balance_usd'],
            //     'deposits_usd' => $userProfile->deposits_usd - $userProfile->withdraws_usd
            // ]);           
        }        
    }

    public function restartAssetPool(AssetPool $assetPool, float $poolStartPriceUsd) : void
    {               
        $BC_BASE = config('app.bc_base'); 

        $assetPool->price_usd = $poolStartPriceUsd;
        $assetPool->save();

        $poolItemsList = AssetPoolItem::where('asset_pool_id', $assetPool->id);                        
        foreach ($poolItemsList as $poolItem) {
            //dd($poolItem->toArray());                                
            $asset = Asset::find($poolItem->asset_id);            
            // $priceUsd = ($poolItem->fraction/$poolStartPriceUsd)*100;
            $priceUsd = bcdiv($poolItem->fraction, $poolStartPriceUsd, $BC_BASE)*100;
            $poolItem->price_usd = $priceUsd;
            // $poolItem->asset_amount = $asset->price_usd > 0 ? $priceUsd/$asset->price_usd : 0;
            $poolItem->asset_amount = $asset->price_usd > 0 ? bcdiv($priceUsd, $asset->price_usd, $BC_BASE) : 0;
            $poolItem->save();                                                
        }
        AssetPoolHistory::where('asset_pool_id', $assetPool->id)->delete();
        AssetPoolHistory::create([
            'asset_pool_id' => $assetPool->id,
            'price_usd' => $assetPool->price_usd
        ]);
        
        $this->rebuildUserPools($assetPool->id);     
    }

    function exchangeBotLog(array $userAssetPoolItemsDiff, $operationMode, int $userId) {
        // dd($userAssetPoolItemsDiff);
        $exchangeBotLog = new ExchangeBotLog();
        if (!empty($userAssetPoolItemsDiff)) {
            foreach ($userAssetPoolItemsDiff as $userAssetPoolItem) {
                $priceUsd = $userAssetPoolItem->price_usd;                        
                $valueCrypto = $userAssetPoolItem->asset_amount;            
                if ($priceUsd > 0) {                
                    $assetName = Asset::find($userAssetPoolItem->asset_pool_item_id)->name;                
                    if ($operationMode == 'sell') {
                        $operationTypeId = ExchangeBotLog::OPERATION_TYPE_ID_ASSET_POOL_SELL_AUTO;
                    } elseif ($operationMode == 'buy') {
                        $operationTypeId = ExchangeBotLog::OPERATION_TYPE_ID_ASSET_POOL_BUY_AUTO;
                    }
                    $exchangeBotLog->commit([
                        'exchange_id' => ExchangeBotLog::EXCHANGE_ID_BINANCE,
                        'operation_type_id' => $operationTypeId,
                        'ex_operation_type_id' => ExchangeBotLog::EXCHANGE_OPERATION_TYPE_ID_EXCHANGE,
                        'asset_pool_item_id' => $userAssetPoolItem->asset_pool_item_id,
                        'user_id' => $userId, 
                        'value_fiat' => $priceUsd,
                        'value_crypto' => $valueCrypto,
                        'api_request' => '> exchange ' . ($operationMode == 'buy' ? "$priceUsd USDT to $valueCrypto $assetName" :  "$valueCrypto $assetName to $priceUsd USDT"),
                        'api_response' => '',
                        'request_ts' => microtime(true),                                
                        'response_ts' => 0,                                
                        'creator_user_id' => auth()->id() ?? 0
                    ]);
                }
            }    
        }        
    }
}