<?php

namespace App\Services;

use App\Models\RatePool;
use App\Models\UserProfile;
use App\Models\UserAssetPoolHistory;
use Carbon\Carbon;

class UserRatePool
{    
    public function dailyProfit($userAssetPool, $date=null, $updateUserProfile=false) {
        $date = $date ?? Carbon::now();

        $userId = $userAssetPool->user_id;                    

        $ratePool = RatePool::with('currency')->find($userAssetPool->rate_pool_id);
        $currencyPriceUsd = $ratePool->currency->price_usd;

        $rate = $ratePool->rate;
        $daysInMonth = $date->daysInMonth;  
        $depositChange = $userAssetPool->price_start * ($rate/100/$daysInMonth);                  
        $depositChangeUsd = $depositChange/$currencyPriceUsd;
        $userAssetPool->price += $depositChange;
        $userAssetPool->price_usd = $userAssetPool->price/$currencyPriceUsd;
        $userAssetPool->save();                    

        $userProfile = UserProfile::where('user_id', $userId)->first();            

        $newBalanceUsd = $userProfile->balance_usd + $depositChangeUsd;

        if ($updateUserProfile) {
            $userProfile->balance_usd = $newBalanceUsd;
            $userProfile->save();
        }

        UserAssetPoolHistory::create([
            'transaction_type' => 'P',
            'user_id' => $userId,
            'asset_pool_id' => 0,
            'rate_pool_id' => $userAssetPool->rate_pool_id,
            'user_asset_pool_id' => $userAssetPool->id,
            'balance_usd' => $newBalanceUsd,
            'deposit_usd' => $depositChangeUsd,
            'admin_id' => 0,
            'created_at' => $date
        ]);
    }
}
