<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\UserAssetPool;
use App\Models\UserAssetPoolItem;
use App\Models\AssetHistory;
use App\Models\AssetPoolHistory;

//use App\Models\UserBalanceHistory;
use App\Models\AssetPoolGroup;
use App\Models\Currency;
use App\Services\UserBalanceStatistics;
use App\Services\SiteHeaderInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(UserAssetPool $userAssetPool, AssetPoolHistory $assetPoolHistory, UserAssetPoolItem $userAassetPoolItem, /*UserBalanceHistory $userBalanceHistory,*/
        UserBalanceStatistics $userBalanceStatistics, AssetHistory $assetHistory) // AssetPoolItem $assetPoolItem
    {
        $poolsToShowCount = 10;
        $assetsToShowCount = 10;

        $BC_BASE = config('app.bc_base');

        $authUser = auth()->user();
        $userId = $authUser->id;

        $userBalanceUsd = $authUser->profile->balance_usd ?? 0;
        $userBalanceUsdt = $authUser->profile->balance_usdt ?? 0;

        // $userBalanceLastInfo = $userBalanceHistory->where('user_id', $userId)->latest('created_at')->first();

        // $userBalance24hUsd = 0;
        // $userBalance24hDiffUsd = 0;
        // $userBalance24hDiffPers = 0;
        // $userBalance24hDiffPers_print = '';
        // if ($userBalanceLastInfo) {
        //     $userBalance24hDiffUsd = $userBalanceLastInfo->balance_usd - $userBalanceLastInfo->deposits_usd;

        //     $userBalance24hDiffPers = $userBalance24hDiffUsd ? round(100*$userBalance24hDiffUsd/$userBalanceLastInfo->deposits_usd, 2) : round($userBalance24hDiffUsd/100, 2);

        //     $userBalance24hDiffPers_print = ($userBalance24hDiffPers > 0 ? '&plus;' : ($userBalance24hDiffPers < 0 ? '&ndash;' : '')) . abs($userBalance24hDiffPers);
        //     $userBalance24hDiffUsd = number_format(abs($userBalance24hDiffUsd), 2);
        // }

        //dd($userBalance24hDiffPers, $userBalance24hDiffUsd);

        $userBalanceStatisticsInfo = [
            '24hours' => ['title' => '1 ' . __('Day'), 'statistics' => $userBalanceStatistics->getStats($userId, '24hours')],
            '7days' => ['title' => '1 ' . __('Weak'), 'statistics' => $userBalanceStatistics->getStats($userId, '7days')],
            '30days' => ['title' => '1 ' . __('Month'), 'statistics' => $userBalanceStatistics->getStats($userId, '30days')],
            '90days' => ['title' => '3 ' . __('Months'), 'statistics' => $userBalanceStatistics->getStats($userId, '90days')],
            // '1yar' => ['title'=> '1 Year', 'statistics' =>  $userBalanceStatistics->getStats($userId, '1yar')],
            'all' => ['title' => __('All time'), 'statistics' => $userBalanceStatistics->getStats($userId, 'all')],
        ];

        //dd($userBalanceStatisticsInfo);

        $userAssetPoolList = $userAssetPool->getByUserId($userId, AssetPoolGroup::G_TYPE_ASSET_POOL);
        $poolIds = $userAssetPoolList->pluck('asset_pool.id');
        $userPoolIds = $userAssetPoolList->pluck('id');
        $poolsHistory = $assetPoolHistory->getPoolHistory(Carbon::now()->subHours(24), $poolIds);

        //dd($userAssetPoolList);

        $userAssetPoolBalance = 0;

        $userAssetPoolList = $userAssetPoolList->map(function ($item) use ($poolsHistory, &$userAssetPoolBalance, $BC_BASE) {
            //$item->share_print = $userBalanceUsd ? round(100*$item->price_usd/$userBalanceUsd, 2) : 0;
            $item->asset_pool->price_usd_print = number_format($item->asset_pool->price_usd, 2);
            // $item->price_usd_print = number_format($item->price_usd, 2);

             $balanceUsd = $item->asset_pool_amount*$item->asset_pool->price_usd;
//            $balanceUsd = bcmul($item->asset_pool_amount, $item->asset_pool->price_usd, $BC_BASE);
            $item->price_usd_print = number_format($balanceUsd, 2);

            $userAssetPoolBalance += $balanceUsd;

            //$item->pool_balance = $item->asset_pool->price_usd ? round($item->price_usd/$item->asset_pool->price_usd, 4) : 0;
            $item->pool_balance = round($item->asset_pool_amount, 4);

            //$fraction = $poolsHistory[$item->asset_pool->id]['fraction'] ?? 0;
            //$prevPriceUsd = 100*$item->price_usd/$fraction;
            //$item->price_diff_usd = $fraction !== 100 ? $fraction*$item->price_usd/(100 - $fraction) : 0;
            $item->price_diff_usd = ($poolsHistory[$item->asset_pool->id]['new_price'] ?? 0) - ($poolsHistory[$item->asset_pool->id]['old_price'] ?? 0);
            $item->price_diff_usd_print = number_format($item->price_diff_usd, 2);

            return $item;
        });

        $userAssetPoolBalancePercentage = $userBalanceUsd > 0 ? round(100 * $userAssetPoolBalance / $userBalanceUsd) : 0;

        //dd($userBalanceUsd, $userAssetPoolBalance);

        $userRatePoolList = $userAssetPool->getByUserId($userId, AssetPoolGroup::G_TYPE_RATE_POOL);
        $ratePoolIds = $userRatePoolList->pluck('rate_pool.id');
        $userRatePoolIds = $userRatePoolList->pluck('id');
        $currencyList = $userRatePoolList->isEmpty() ? [] : Currency::all()->keyBy('id');
        //$poolsHistory = $assetPoolHistory->getPoolHistory(Carbon::now()->subHours(24), $poolIds);

        // dd($userRatePoolList->toArray());
        $userRatePoolBalance = 0;
        //$daysInMonth = Carbon::now()->daysInMonth;
        $userRatePoolList = $userRatePoolList->map(function ($item) use ($userBalanceUsd, $currencyList, &$userRatePoolBalance, $BC_BASE) {
            $poolRate = $item->rate_pool->rate ?? 0;
            $currencyId = $item->rate_pool->currency_id ?? 0;
            //$poolRateDay = $poolRate/$daysInMonth;
            // $item->share_print = $userBalanceUsd ? round(100*$item->price_usd/$userBalanceUsd, 2) : 0;
            $item->share_print = $userBalanceUsd ? round(100 * bcdiv($item->price_usd, $userBalanceUsd, $BC_BASE), 2) : 0;
            //$item->rate_pool->price_usd_print = number_format($item->rate_pool->price_usd, 2);
            // $item->price_usd_print = number_format($item->price_usd, 2);
            $item->price_print = number_format($item->price, 2);
            $item->price_usd_print = number_format($item->price_usd, 2);
            $item->rate_pool->rate_print = round($poolRate, 2);
            $item->currency_symbol_short = $currencyList[$currencyId]['symbol_short'] ?? '';
            //$created = new Carbon($item->rate_date_start);
            //$diffDays = $created->diff(Carbon::now())->days;
            //$item->rate_total_print = round($diffDays*$poolRateDay, 2);
            //dd($item->toArray());
            $item->rate_total_print = number_format($item->price - $item->price_start, 2);
            //$item->pool_balance = round($item->asset_pool_amount, 4);

            $userRatePoolBalance += $item->price_usd;

            //$fraction = $poolsHistory[$item->rate_pool->id]['fraction'] ?? 0;

            //$item->price_diff_usd = $fraction !== 100 ? $fraction*$item->price_usd/(100 - $fraction) : 0;
            //$item->price_diff_usd_print = number_format($item->price_diff_usd, 2);

            return $item;
        });

        $userRatePoolBalancePercentage = 100 - $userAssetPoolBalancePercentage;

        // dd($userRatePoolList->toArray());

        //dd($poolIds->toArray());

        $assetPoolItemList = $userAassetPoolItem->getByUserPoolId($userPoolIds);
        //dd($assetPoolItemList->toArray());
        $assetItemList = [];
        $assetItemPriceSum = 0;
        $assetIds = [];
        foreach ($assetPoolItemList as $itemsPool) {
            foreach ($itemsPool as $item) {
                if (!isset($assetItemList[$item->asset_id])) {
                    $assetIds[$item->asset_id] = $item->asset_id;
                    $assetItemList[$item->asset_id] = [
                        'price_usd' => $item->price_usd,
                        'asset_amount' => $item->asset_amount,
                        'pools_count' => 1,
                        'asset_id' => $item->asset_id,
                        'asset_name' => $item->asset_name,
                        'asset_symbol' => $item->symbol,
                        'asset_logo' => $item->logo,
                        'asset_slug' => $item->asset_slug,
                        'asset_price_usd' => $item->asset_price_usd,
                    ];
                } else {
                    $assetItemList[$item->asset_id]['price_usd'] += $item->price_usd;
                    $assetItemList[$item->asset_id]['asset_amount'] += $item->asset_amount;
                    $assetItemList[$item->asset_id]['pools_count'] += 1;
                }
                $assetItemPriceSum += $item->price_usd;
            }
        }
        usort($assetItemList, function ($a, $b) {
            return $b['price_usd'] - $a['price_usd'];
        });

        $assetHistoryList = $assetHistory->getPoolsHistory(Carbon::now()->subHours(24), $assetIds);

        $assetItemList = collect($assetItemList)->map(function ($item) use ($assetItemPriceSum, $assetHistoryList, $BC_BASE) {
            $item['price_usd_print'] = number_format($item['price_usd'], 2);
            $item['asset_amount_print'] = number_format($item['asset_amount'], 6);
            // $item['fraction'] = $assetItemPriceSum ? 100*$item['price_usd']/$assetItemPriceSum : 0;
            $item['fraction'] = $assetItemPriceSum ? 100 * bcdiv($item['price_usd'], $assetItemPriceSum, $BC_BASE) : 0;
            $item['fraction_print'] = number_format($item['fraction'], 2);
            //$item['asset_price_usd_print'] = number_format($item['asset_price_usd'], 2);

            $hist = $assetHistoryList[$item['asset_id']] ?? null;
            if ($hist) {
                // $keff = $hist['new_price'] > 0 ? $hist['old_price']/$hist['new_price'] : 0;
                $keff = $hist['new_price'] > 0 ? bcdiv($hist['old_price'], $hist['new_price'], $BC_BASE) : 0;
                // $item['history_price_change_usd'] = $item['asset_price_usd'] - $keff*$item['asset_price_usd'];
                $item['history_price_change_usd'] = $item['asset_price_usd'] - bcmul($keff, $item['asset_price_usd'], $BC_BASE);
            }
            $item['history_price_change_usd_print'] = !empty($item['history_price_change_usd']) ? number_format(abs($item['history_price_change_usd']), 2) : 0;
            //dd($hist, $keff, $item['asset_price_usd'], $item['history_price_change_usd']);

            return $item;
        })->all();

        $siteHeaderInfo = new SiteHeaderInfo();
        $teatherInfo = $siteHeaderInfo->getCryptoCurrencyInfo([Asset::USDT_ID], 3)->first();
        //dd($teatherInfo);

        //dd($userRatePoolList->toArray());
        //dd($assetItemList);
        //dd($assetItemList);
        //dd($assetPoolItemList->toArray());

        return view(
            'home.index',
            compact('userAssetPoolList', 'poolsHistory', 'assetItemList', 'userBalanceStatisticsInfo', 'poolsToShowCount', 'assetsToShowCount',
                'userRatePoolList', 'userAssetPoolBalance', 'userBalanceUsd', 'userBalanceUsdt', 'teatherInfo',
                'userRatePoolBalance', 'userRatePoolBalancePercentage', 'userAssetPoolBalancePercentage') +
            ['assetHistory' => $assetHistoryList]
        // compact('userAssetPoolList', 'poolsHistory', 'assetItemList', 'userBalanceStatisticsInfo', 'poolsToShowCount', 'assetsToShowCount',
        //     'userBalance24hDiffPers', 'userBalance24hDiffPers_print', 'userBalance24hDiffUsd', 'userRatePoolList', 'userAssetPoolBalance',
        //     'userRatePoolBalance', 'userRatePoolBalancePercentage', 'userAssetPoolBalancePercentage') +
        // ['assetHistory' => $assetHistoryList]
        );
    }

    public function getUserBalance() : array
    {
        $usd = 0.00;
        $usdt = 0.00;

        if($userId = auth()->user()?->id){
            $userData = DB::table('users as u')
                ->leftJoin('user_profiles as up', 'u.id', '=', 'up.user_id')
                ->selectRaw('u.*, up.balance_usd, up.balance_usdt')
                ->where('u.id', $userId)->first();


            $usd = $userData?->balance_usd ?? $usd;
            $usdt = $userData?->balance_usdt ?? $usdt;
        }

        $usd = number_format($usd, 2);
        $usd = explode('.', $usd);

        $usdt = number_format($usdt, 2);
        $usdt = explode('.', $usdt);

        return [
            "usd" => ' $' . "{$usd[0]}.{$usd[1]}",
            "usdt" => " {$usdt[0]}.{$usd[1]}",
        ];
    }
}
