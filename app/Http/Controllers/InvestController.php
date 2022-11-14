<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetPool;
use App\Models\AssetPoolHistory;
use App\Models\AssetHistory;
use App\Models\RatePool;
use App\Models\UserAssetPool;
use App\Models\UserAssetPoolHistory;
use App\Services\AssetPoolStatistics;
use Carbon\Carbon;

class InvestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(AssetPool $assetPool, AssetPoolHistory $assetPoolHistory, RatePool $ratePool,Request $request)
    {
        $period = $request->period?$request->period:'24hours';
        $period_date = Carbon::now()->subHours(24);
        if ($period == 'ALL') {
            $period_date = AssetPoolHistory::first()->created_at;
        } elseif ($period == '7days') {
            $period_date = Carbon::now()->subDays(7);
        } elseif ($period == '30days') {
            $period_date = Carbon::now()->subDays(30);
        } elseif ($period == '90days') {
            $period_date = Carbon::now()->subDays(90);
        } elseif ($period == '1yar') {
            $period_date = Carbon::now()->subDays(365);
        }
        $dbLocaleSufix = app()->getLocale() == 'en' ? '' : '_'.app()->getLocale();
        $pools = $assetPool->getAll(0, 1);
        $poolIds = $pools->pluck('id');
        $poolsHistory = $assetPoolHistory->getPoolHistory($period_date, $poolIds);
        $poolsGrouped = $pools->groupBy('asset_pool_group_id');

        $userRatePoolIds = UserAssetPool::where('user_id', auth()->id())->where('rate_pool_id', '>', 0)
                                        ->select('rate_pool_id')
                                        ->get()
                                        ->pluck('rate_pool_id')
                                        ->toArray();
        $ratePools = $ratePool->getAll(0, 1)->filter(fn($item) => in_array($item->id, $userRatePoolIds));
        // dd($ratePools->toArray());
        // $ratePoolIds = $ratePools->pluck('id');
        // $ratePoolsHistory = $assetPoolHistory->getPoolHistory(Carbon::now()->subHours(24), $ratePoolIds);
        $ratePoolsGrouped = $ratePools->groupBy('asset_pool_group_id');
//        dd($poolsHistory);
        // dd($ratePoolsGrouped->toArray());

        return view('invest.index', compact('poolsGrouped', 'poolsHistory', 'ratePoolsGrouped', 'dbLocaleSufix', 'period'));
    }
    /**
     * Shows asset pool card.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function pool(AssetPool $assetPool, AssetPoolHistory $assetPoolHistory, AssetPoolStatistics $assetPoolStatistics, AssetHistory $assetHistory)
    {
        $BC_BASE = config('app.bc_base');
        $dbLocaleSufix = app()->getLocale() == 'en' ? '' : '_'.app()->getLocale();

        //$poolsList = AssetPool::where('is_active', 1)->get();
        $poolId = $assetPool->id;

        $pool = $assetPool->getAll($poolId, 1);
        $pool = $pool[0];
        $assets = $pool['assets'];

        $metaTitle = $pool->name;

        // $pool->price_start_usd_print = number_format($pool->price_start_usd, 2);
        // $pool->created_at_print = Carbon::parse($pool->created_at)->format('d.m.y H:i');

        $pool->rebalanced_at_print = $pool->rebalanced_at ? Carbon::parse($pool->rebalanced_at)->format('d.m.y H:i') : 'not yet';
        $pool->rebalanced_next_at_print = $assetPool->getRebalanceDateNext($pool->rebalanced_at, $pool->rebalance_frequency);

        //dd($assets->toArray());
        //dd($assets->pluck('asset_id')->toArray());
        // $poolLastHistory = $assetPoolHistory->getLastPoolsHistory($poolId);
        // $poolLastHistory = $poolLastHistory[$poolId] ?? [];

        $poolHistory = $assetPoolHistory->getPoolHistory(Carbon::now()->subHours(24), $poolId);
        $poolHistoryStartPoint = $assetPoolHistory->getPoolStartPoint($poolId);
        $startPointPriceUsd = $poolHistoryStartPoint->price_usd ?? 0;
        $startPointCreatedAt = $poolHistoryStartPoint->created_at ?? null;
        //dd($poolHistoryStartPoint);
        $startPointPriceUsd_print = number_format($startPointPriceUsd, 2);
        $startPointCreatedAt_print = $startPointCreatedAt ? Carbon::parse($startPointCreatedAt)->format('d.m.y H:i') : '';

        $poolLastHistory = empty($poolHistory) ? [] : array_shift($poolHistory);

        // dd($poolLastHistory);

        $poolStatistics = [
            '24hours' => ['title'=> '1 '.__('Day'), 'statistics' => $poolLastHistory],
            '7days' => ['title'=> '1 '.__('Weak'), 'statistics' => null],
            '30days' => ['title'=> '1 '.__('Month'), 'statistics' => null],
            '90days' => ['title'=> '3 '.__('Months'), 'statistics' => null],
            'all' => ['title'=> __('All time'), 'statistics' => null]
            // '1yar' => ['title'=> '1 Year', 'statistics' => $assetPoolStatistics->getStats($poolId, '1yar')],
        ];

        if ( !is_null($poolStatistics['7days']['statistics']) ) {
            $poolStatistics['30days']['statistics'] = $assetPoolStatistics->getStats($poolId, '30days');
        }

        if ( !is_null($poolStatistics['30days']['statistics']) ) {
            $poolStatistics['90days']['statistics'] = $assetPoolStatistics->getStats($poolId, '90days');
        }

        if ( !is_null($poolStatistics['90days']['statistics']) ) {
            $poolStatistics['all']['statistics'] = $assetPoolStatistics->getStats($poolId, 'all');
        }

        $assetIds = $assets->pluck('asset_id')->toArray();

        $assetHistoryList = $assetHistory->getPoolsHistory(Carbon::now()->subHours(24), $assetIds);

        $assets = $assets->map(function ($item) use ($assetHistoryList, $BC_BASE) {
            $hist = $assetHistoryList[$item->asset_id] ?? null;
            if ($hist) {
                // $keff = $hist['new_price'] > 0 ? $hist['old_price']/$hist['new_price'] : 0;
                $keff = $hist['new_price'] > 0 ? bcdiv($hist['old_price'], $hist['new_price'], $BC_BASE) : 0;
                // $item->history_price_change_usd = $item->asset_price_usd - $keff*$item->asset_price_usd;
                $item->history_price_change_usd = $item->asset_price_usd - bcmul($keff, $item->asset_price_usd, $BC_BASE);
            }
            //$item->history_price_change_usd_print = !empty($item->history_price_change_usd) ? number_format(abs($item->history_price_change_usd), 2) : 0;
            //dd($hist, $keff, $item['asset_price_usd'], $item['history_price_change_usd']);

            return $item;
        });

        //dd($assetHistoryList);

        return view('invest.pool', compact('pool', 'poolLastHistory',  'assets', 'poolStatistics', 'metaTitle', 'assetHistoryList',
                                        'startPointCreatedAt_print', 'startPointPriceUsd_print', 'dbLocaleSufix'));
    }

    /**
     * Shows asset pool card.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ratePool(RatePool $ratePool, AssetPoolHistory $assetPoolHistory, AssetPoolStatistics $assetPoolStatistics)
    {
        $BC_BASE = config('app.bc_base');
        $dbLocaleSufix = app()->getLocale() == 'en' ? '' : '_'.app()->getLocale();

        //$poolsList = AssetPool::where('is_active', 1)->get();
        $poolId = $ratePool->id;
        $userId = auth()->id();

        $pool = $ratePool->getAll($poolId, 1);
        if ($pool->isEmpty()) {
            abort(404);
        }
        $pool = $pool[0];

        $metaTitle = $pool->name;

        $userRatePool = UserAssetPool::where('user_id', $userId)->where('rate_pool_id', $poolId)->firstOrFail();

        // "price_start_usd" => "25.000"
//   "price_usd" => "25.000"
//   "price_start" => "25.000000"
//   "price" => "25.000000"
//   "rate_date_start" => "2022-10-10"
//   "rate_date_end" => "2022-10-15"

        $userRatePool->date_start_print = $userRatePool->rate_date_start ? Carbon::parse($userRatePool->rate_date_start)->format('d.m.y') : '';
        $userRatePool->date_end_print = $userRatePool->rate_date_end ? Carbon::parse($userRatePool->rate_date_end)->format('d.m.y') : '';
        $userRatePool->price_start_usd_print = number_format($userRatePool->price_start_usd, 2);

        //dd($userRatePool->toArray());

        $userPoolHistory = (new UserAssetPoolHistory())->getRatePoolHistory($poolId, $userId, UserAssetPoolHistory::TRANSACTION_TYPE_PERCENTAGE);

        $userPoolHistory = $userPoolHistory->map(function($item, $i) use ($userRatePool, $BC_BASE) {
            $item->pool_balance_usd = $userRatePool->price_usd - bcmul(($i+1), $item->deposit_usd, $BC_BASE);
            $item->pool_balance_usd_print = number_format($item->pool_balance_usd, 2);
            $item->date_print = Carbon::parse($item->created_at)->format('d.m.y');
            return $item;
        });

        //dd($userPoolHistory->toArray());

        $assets = [];

        return view('invest.rate_pool', compact('pool', 'userRatePool', 'userPoolHistory', 'metaTitle', 'assets', 'dbLocaleSufix'));
        // return view('invest.rate_pool', compact('pool', 'poolLastHistory',  'assets', 'poolStatistics', 'metaTitle', 'assetHistoryList',
        //                                 'startPointCreatedAt_print', 'startPointPriceUsd_print', 'dbLocaleSufix'));
    }
}
