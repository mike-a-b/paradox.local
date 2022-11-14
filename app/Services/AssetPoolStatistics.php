<?php

namespace App\Services;

use App\Models\AssetPool;
use App\Models\AssetPoolHistory;
use Carbon\Carbon;

class AssetPoolStatistics
{
    public function getStats($poolId, $period='24hours') {
        $firstPointInfo =
            AssetPoolHistory::where('asset_pool_id', $poolId)
                ->where(function($query) use ($period) {
                    if ($period == '24hours') {
                        $query->where('created_at', '>=', Carbon::now()->subHours(24))
                                ->where('created_at', '<', Carbon::now()->subHours(23));
                    } elseif ($period == '7days') {
                        $query->where('created_at', '>=', Carbon::now()->subDays(7))
                                ->where('created_at', '<', Carbon::now()->subDays(6));
                    } elseif ($period == '30days') {
                        $query->where('created_at', '>=', Carbon::now()->subDays(30))
                                ->where('created_at', '<', Carbon::now()->subDays(29));
                    } elseif ($period == '90days') {
                        $query->where('created_at', '>=', Carbon::now()->subDays(90))
                                ->where('created_at', '<', Carbon::now()->subDays(89));
                    } elseif ($period == '1yar') {
                        $query->where('created_at', '>=', Carbon::now()->subDays(365))
                                ->where('created_at', '<', Carbon::now()->subDays(364));
                    } elseif ($period == 'all') {
                        $query->where('price_usd', '!=', '');
                    }
                })
                ->orderBy('created_at', 'ASC')
                ->first();

        if (empty($firstPointInfo)) {
            return null;
        }

        $lastPointInfo =
            AssetPoolHistory::where('asset_pool_id', $poolId)
                ->orderBy('created_at', 'DESC')
                ->first();

        $newPrice = $lastPointInfo->price_usd;
        $oldPrice = $firstPointInfo->price_usd;

        return [
                'new_price' => $newPrice,
                'old_price' => $oldPrice,
                'direction' => round($newPrice, 4) == round($oldPrice, 4) ? 'same' : ($newPrice > $oldPrice ? 'up' : 'down'),
                'fraction' => round($newPrice, 4) != round($oldPrice, 4) && $oldPrice ? round(100*abs($newPrice - $oldPrice)/$oldPrice, 2) : 0
            ];
    }
}
