<?php

namespace App\Services;

//use App\Models\AssetPool;
use App\Models\UserBalanceHistory;
use Carbon\Carbon;

class UserBalanceStatistics
{    
    public function getStats($userId, $period='24hours') {
        $firstPointInfo = 
            UserBalanceHistory::where('user_id', $userId)
                ->where(function($query) use ($period) {
                    if ($period == '24hours') {
                        $query->where('created_at', '>=', Carbon::now()->subHours(24));
                    } elseif ($period == '7days') {
                        $query->where('created_at', '>=', Carbon::now()->subDays(7));
                    } elseif ($period == '30days') {
                        $query->where('created_at', '>=', Carbon::now()->subDays(30));
                    } elseif ($period == '90days') {
                        $query->where('created_at', '>=', Carbon::now()->subDays(90));
                    } elseif ($period == '1yar') {
                        $query->where('created_at', '>=', Carbon::now()->subDays(365));
                    } else {
                        $query->where('balance_usd', '>', 0);
                    }
                })
                ->orderBy('created_at', 'ASC')
                ->first();

        if (empty($firstPointInfo)) {
            return null;
        }

        $lastPointInfo = 
            UserBalanceHistory::where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->first();

        //if ($period == 'all')
        //dd($firstPointInfo->toArray(), $lastPointInfo->toArray());

        $newPrice = $lastPointInfo->balance_usd; // - $lastPointInfo->deposits_usd;
        $oldPrice = $firstPointInfo->balance_usd; // - $firstPointInfo->deposits_usd;

        // if ($period=='all') {
        //     dd($newPrice, $oldPrice);
        // }        

        $ret = [                
                'new_price' => $newPrice,
                'old_price' => $oldPrice,
                'direction' => round($newPrice, 4) == round($oldPrice, 4) ? 'same' : ($newPrice > $oldPrice ? 'up' : 'down'),
                'fraction' => $oldPrice > 0 && round($newPrice, 4) != round($oldPrice, 4) ? abs(round(100*($newPrice - $oldPrice)/$oldPrice, 2)) : 0,
                'fraction_usd' => $oldPrice > 0 ? number_format(abs($newPrice - $oldPrice), 2) : 0
            ];
        //dd($ret);
        
        return $ret;
    }
}
