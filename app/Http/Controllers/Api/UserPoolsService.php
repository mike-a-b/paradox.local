<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Services\UserPoolOperationsService;
use App\Services\BladeService;
use App\Models\AssetPoolGroup;
use App\Models\UserAssetPool;
use App\Models\Commission;
use App\Models\UserProfile;
use Illuminate\Validation\ValidationException;

class UserPoolsService extends Controller
{
    public function assetPools(Request $request)
    {
        $request->validate([
            'pool_id' => 'integer|gt:0'
        ]);

        $poolId = $request->get('pool_id', 0);

        $userId = auth()->user()->id;

        $BC_BASE = config('app.bc_base');

        // $userAssetPoolList = $userAssetPool->getByUserId($userId, AssetPoolGroup::G_TYPE_ASSET_POOL);

        $dataList = DB::table('asset_pools as a_pools')
                        ->leftJoin('asset_pool_groups as ap_groups', 'ap_groups.id', '=', 'a_pools.asset_pool_group_id')
                        ->leftJoin('user_asset_pools as u_pools', function ($join) use ($userId) {
                            $join->on('a_pools.id', '=', 'u_pools.asset_pool_id')
                                 ->where('u_pools.user_id', $userId);
                        })
                        //->leftJoin('rate_pools as r_pools', 'r_pools.id', '=', 'uap_history.rate_pool_id')
                        ->selectRaw('a_pools.id, a_pools.asset_pool_group_id, a_pools.name, a_pools.name_short, a_pools.price_usd,
                                    ap_groups.name as group_name, u_pools.price_usd as user_price_usd, u_pools.asset_pool_amount,
                                    a_pools.logo as pool_logo')
                        ->where('a_pools.is_active', 1)
                        ->when($poolId, function ($query) use ($poolId) {
                            $query->where('a_pools.id', $poolId);
                        })
                        ->orderBy('u_pools.price_usd', 'DESC')
                        ->orderBy('ap_groups.pos', 'ASC')
                        ->get();

        //dd($dataList->toArray());
        $commissions = Commission::whereIn('type_id', [Commission::TYPE_ID_ASSET_POOL_BUY, Commission::TYPE_ID_ASSET_POOL_SELL])
                            ->get()
                            ->pluck('value', 'type_id');

        $list = $dataList->map(function($item) use ($commissions, $BC_BASE) {
            $ret = (object)[];
            $ret->pool_id = $item->id;
            $ret->pool_name = $item->name;
            $ret->pool_name_short = $item->name_short;
            $ret->pool_logo = $item->pool_logo;
            $ret->pool_usd_print = number_format($item->price_usd, 2);

            // $balanceUsd = $item->asset_pool_amount*$item->price_usd;
            $balanceUsd = bcmul($item->asset_pool_amount, $item->price_usd, $BC_BASE);
            $ret->price_usd = $balanceUsd;
            $ret->price_usd_print = number_format($balanceUsd, 2);

            $ret->commission_buy = floatval($commissions[Commission::TYPE_ID_ASSET_POOL_BUY]);
            $ret->commission_sell = floatval($commissions[Commission::TYPE_ID_ASSET_POOL_SELL]);

            // $item->pool_balance = round($item->asset_pool_amount, 4);

            return $ret;
        });

        // dd($list->toArray());

        return [
            'data' => $list,
            'satatus' => 'success'
        ];
    }

    public function ratePools()
    {
        $userId = auth()->user()->id;

        $dataList = DB::table('rate_pools as r_pools')
                        ->leftJoin('user_asset_pools as u_pools', function ($join) use ($userId) {
                            $join->on('r_pools.id', '=', 'u_pools.rate_pool_id')
                                 ->where('u_pools.user_id', $userId);
                        })
                        //->leftJoin('rate_pools as r_pools', 'r_pools.id', '=', 'uap_history.rate_pool_id')
                        ->selectRaw('r_pools.id, r_pools.name, r_pools.rate,
                                    u_pools.price_usd, u_pools.price, r_pools.logo as pool_logo')
                        ->orderBy('u_pools.price_usd', 'DESC')
                        ->orderBy('r_pools.rate', 'ASC')
                        ->get();

        //dd($dataList->toArray());

        $list = $dataList->map(function($item) {
            $ret = (object)[];
            $ret->pool_id = $item->id;
            $ret->pool_name = $item->name;
            $ret->pool_logo = $item->pool_logo;
            //$ret->pool_usd_print = number_format($item->rate_pool->price_usd, 2);
            $poolRate = $item->rate ?? 0;
            // $currencyId = $item->rate_pool->currency_id ?? 0;

            $ret->price_print = number_format($item->price, 2);
            $ret->price_usd = $item->price_usd;
            $ret->price_usd_print = number_format($item->price_usd, 2);
            $ret->pool_rate = $poolRate;
            $ret->pool_rate_print = round($poolRate, 2);

            return $ret;
        });

        return [
            'data' => $list,
            'satatus' => 'success'
        ];
    }

    public function userAssetPools(Request $request, UserAssetPool $userAssetPool)
    {
        $request->validate([
            'pool_id' => 'integer|gt:0'
        ]);

        $poolId = $request->get('pool_id', 0);

        $userId = auth()->user()->id;

        $BC_BASE = config('app.bc_base');

        $userAssetPoolList = $userAssetPool->getByUserId($userId, AssetPoolGroup::G_TYPE_ASSET_POOL, $poolId);

        $list = $userAssetPoolList->map(function($item) use ($BC_BASE) {
            $ret = (object)[];
            $ret->pool_id = $item->asset_pool->id;
            $ret->pool_name = $item->asset_pool->name;
            $ret->pool_name_short = $item->asset_pool->name_short;
            $ret->pool_logo = $item->asset_pool->logo;
            $ret->pool_usd_print = number_format($item->asset_pool->price_usd, 2);

            // $balanceUsd = $item->asset_pool_amount*$item->asset_pool->price_usd;
            $balanceUsd = bcmul($item->asset_pool_amount, $item->asset_pool->price_usd, $BC_BASE);
            $ret->price_usd = $balanceUsd;
            $ret->price_usd_print = number_format($balanceUsd, 2);

            // $item->pool_balance = round($item->asset_pool_amount, 4);

            return $ret;
        });

        return [
            'data' => $list,
            'satatus' => 'success'
        ];
    }

    public function userRatePools(UserAssetPool $userAssetPool)
    {
        $userId = auth()->user()->id;

        $userRatePoolList = $userAssetPool->getByUserId($userId, AssetPoolGroup::G_TYPE_RATE_POOL);

        //dd($userRatePoolList);

        $list = $userRatePoolList->map(function($item) {
            $ret = (object)[];
            $ret->pool_id = $item->rate_pool->id;
            $ret->pool_name = $item->rate_pool->name;
            //$ret->pool_usd_print = number_format($item->rate_pool->price_usd, 2);
            $poolRate = $item->rate_pool->rate ?? 0;
            // $currencyId = $item->rate_pool->currency_id ?? 0;

            $ret->price_print = number_format($item->price, 2);
            $ret->price_usd_print = number_format($item->price_usd, 2);
            $ret->pool_rate = $poolRate;
            $ret->pool_rate_print = round($poolRate, 2);
            // $ret->currency_symbol_short = $currencyList[$currencyId]['symbol_short'] ?? '';
            // $ret->rate_total_print = number_format($item->price - $item->price_start, 2);

            return $ret;
        });

        return [
            'data' => $list,
            'satatus' => 'success'
        ];
    }

    public function userProfile(UserProfile $userProfile)
    {
        $bladeService = new BladeService();

        $userId = auth()->user()->id;

        $ret = $userProfile->where('user_id', $userId)->select(['balance_usd', 'balance_usdt'])->firstOrFail();
        $ret->balance_usd_print = $ret->balance_usd ? $bladeService->printPriceBig($ret->balance_usd) : '';
        $ret->balance_usdt_print = $ret->balance_usd ? $bladeService->printPriceBig($ret->balance_usdt) : '';

        return [
            'data' => $ret,
            'satatus' => 'success'
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return array|\Illuminate\Http\Response
     */
    public function poolBalanceUpdate(Request $request)
    {
        $bladeService = new BladeService();

        $request->validate([
            'pool_type' => ['required', 'string', Rule::in(['assets-pool', 'rate-pool'])],
            'operation_type' => ['required', 'string', Rule::in(['sell', 'buy'])],
            'pool_id' => 'required|integer|gt:-1',
            'sum' => 'required|numeric|gte:0.01',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date',
        ]);

        $user = auth()->user();
        $userId = $user->id;
        $poolType = $request->post('pool_type');
        $operationType = $request->post('operation_type');
        $poolId = $request->post('pool_id');
        $sum = $request->post('sum');
        $dateStart = $request->post('date_start');
        $dateEnd = $request->post('date_end');

        // $priceStartUsd = 0;
        if ($poolType === 'assets-pool') {
            $userAssetPool = UserAssetPool::where('asset_pool_id', $poolId)->where('user_id', $userId)->first();
            if ($operationType === 'sell') {
                if (empty($userAssetPool->price_usd)) {
                    throw $this->balanceUpdateValidationError(__('messages.no_bought_ap'));
                }
                $priceUsd = $userAssetPool->price_usd - $sum;
                if ($priceUsd <= 0) {
                    throw $this->balanceUpdateValidationError(__('messages.not_enough_ap_balance', ['balance' => $priceUsd]));
                }
                $params = ['price_usd' => $priceUsd];
                $responseData = (new UserPoolOperationsService)->updateAssetPool($params, $userAssetPool);

            } elseif ($operationType === 'buy') {
                $userProfile = UserProfile::where('user_id', $userId)->firstOrFail();
                if ($sum > $userProfile->balance_usdt) {
                    $diff = round($userProfile->balance_usdt - $sum, 2);
                    throw $this->balanceUpdateValidationError(__('messages.not_enough_usdt_balance', ['balance' => $diff]));
                }
                if ($userAssetPool) {
                    $priceUsd = $userAssetPool->price_usd + $sum;
                    $params = ['price_usd' => $priceUsd];
                    $responseData = (new UserPoolOperationsService)->updateAssetPool($params, $userAssetPool);
                } else {
                    $params = [
                        'price_start_usd' => $sum,
                        'asset_pool_id' => $poolId,
                    ];
                    $responseData = (new UserPoolOperationsService)->createAssetPool($params, $user);
                }
            }
        } elseif ($poolType === 'rate-pool') {
            if ($operationType === 'sell') {
                $userAssetPool = UserAssetPool::where('rate_pool_id', $poolId)->where('user_id', $userId)->firstOrFail();
                $priceStartUsd = $userAssetPool->price_start_usd - $sum;
                if ($priceStartUsd <= 0) {
                    throw $this->balanceUpdateValidationError(__('messages.not_enough_rp_balance', ['balance' => $priceStartUsd]));
                }
                $params = ['price_start' => $priceStartUsd];
                $responseData = (new UserPoolOperationsService)->updateRatePool($params, $userAssetPool);

            } elseif ($operationType === 'buy') {
                $userProfile = UserProfile::where('user_id', $userId)->firstOrFail();
                $userAssetPool = UserAssetPool::where('rate_pool_id', $poolId)->where('user_id', $userId)->first();
                if ($sum > $userProfile->balance_usdt) {
                    $diff = round($userProfile->balance_usdt - $sum, 2);
                    throw $this->balanceUpdateValidationError(__('messages.not_enough_usdt_balance', ['balance' => $diff]));
                }
                if ($userAssetPool) {
                    $priceStartUsd = $userAssetPool->price_start_usd + $sum;
                    $params = ['price_start' => $priceStartUsd];
                    $responseData = (new UserPoolOperationsService)->updateRatePool($params, $userAssetPool);
                } else {
                    $params = [
                        'rate_pool_id' => $poolId,
                        'price_start' => $sum,
                        'date_start' => $dateStart,
                        'date_end' => $dateEnd,
                    ];
                    $responseData = (new UserPoolOperationsService)->createRatePool($params, $user);
                }
            }
        }

        //dd($responseData['pool_data']);

        $balanceUsd = $responseData['user_updates_data']['user_profile']->balance_usd ?? null;
        $balanceUsdt = $responseData['user_updates_data']['user_profile']->balance_usdt ?? null;

        return [
            'data' => [
                'balance_usd' => $balanceUsd,
                'balance_usdt' => $balanceUsdt,
                'balance_usd_print' => $balanceUsd ? $bladeService->printPriceBig($balanceUsd) : '',
                'balance_usdt_print' => $balanceUsdt ? $bladeService->printPriceBig($balanceUsdt) : '',
                // 'pool_id' => $poolInfo->id,
                // 'pool_balance_usd' => $responseData['pool_data']->price_usd,
            ],
            'satatus' => 'success'
        ];
    }

    public function balanceUpdateValidationError($message) {
        return
            ValidationException::withMessages(['sum' => $message]);
    }
}
