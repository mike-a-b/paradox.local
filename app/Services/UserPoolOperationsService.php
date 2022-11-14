<?php

namespace App\Services;

use App\Models\User;
use App\Models\Asset;
use App\Models\RatePool;
use App\Models\UserProfile;
use App\Models\UserAssetPoolHistory;
use App\Models\UserBalanceHistory;
use App\Models\UserAssetPool;
use App\Models\UserAssetPoolItem;
use App\Models\Commission;
use App\Models\Transaction;
use App\Models\ExchangeInfo;
use App\Models\ExchangeBotLog;
use Carbon\Carbon;

class UserPoolOperationsService
{
    public function createAssetPool(array $request, User $user) {

        $poolPriceBaseUsd = $request['price_start_usd'];
        $assetPoolId = $request['asset_pool_id'];

        $commissionValueUsd = Commission::calculateCommission(Commission::TYPE_ID_ASSET_POOL_BUY, $poolPriceBaseUsd);

        $poolPriceStartUsd = $poolPriceBaseUsd - $commissionValueUsd;

        //dd($poolPriceStartUsd, $poolPriceStartUsdBase, $commissionValueUsd);

        $userAssetPool = UserAssetPool::create([
            'user_id' => $user->id,
            'asset_pool_id' => $assetPoolId,
            'price_start_usd' => $poolPriceStartUsd,
            'price_usd' => $poolPriceStartUsd
        ]);

        $userAssetPoolItems = (new UserAssetPoolItem())->initAllByUserPoolId($userAssetPool);

        $userAssetPool->setPoolAmount();

        $this->registerCommission(Transaction::TYPE_ID_ASSET_POOL_BUY, $user->id, $poolPriceBaseUsd, $commissionValueUsd, $assetPoolId);

        $userUpdatesData = $this->userHistoryUpdates('C', $poolPriceBaseUsd, $user->id, $userAssetPool->id, ['asset_pool_id' => $userAssetPool->asset_pool_id], $commissionValueUsd);

        $this->exchangeBotLog($userAssetPoolItems['diff'], 'create', $user->id);

        return [
            // 'asset_pool' => $userAssetPool,
            'user_updates_data' => $userUpdatesData,
        ];
    }

    public function createRatePool(array $request, User $user) {

        $poolPriceStart = $request['price_start'];
        $ratePoolId = $request['rate_pool_id'];
        $dateStart = $request['date_start'];
        $dateEnd = $request['date_end'];

        $ratePool = RatePool::with('currency')->find($ratePoolId);
        $currencyPriceUsd = $ratePool->currency->price_usd;

        $poolPriceStartUsd = $poolPriceStart/$currencyPriceUsd;

        $userRatePool = new UserRatePool();

        $userAssetPool = UserAssetPool::create([
            'user_id' => $user->id,
            'rate_pool_id' => $ratePoolId,
            'price_start' => $poolPriceStart,
            'price' => $poolPriceStart,
            'price_start_usd' => $poolPriceStartUsd,
            'price_usd' => $poolPriceStartUsd,
            'rate_date_start' => $dateStart,
            'rate_date_end' => $dateEnd,
        ]);

        $userAssetPool->setPoolAmount();

        $userUpdatesData = $this->userHistoryUpdates('C', $poolPriceStartUsd, $user->id, $userAssetPool->id, ['rate_pool_id' => $userAssetPool->rate_pool_id]);

        $dateStart = new Carbon($dateStart);
        $diffDays = Carbon::now()->diff($dateStart)->days;
        if ($diffDays > 0) {
            $createdAt = Carbon::now()->subDays($diffDays);
            $userAssetPool->created_at = $createdAt;
            $userAssetPool->save();
            $userAssetPoolHistory = $userUpdatesData['user_asset_pool_history'];
            $userAssetPoolHistory->created_at = $createdAt;
            $userAssetPoolHistory->save();
            for ($i = $diffDays; $i > 0; $i--) {
                $datePast = Carbon::now()->subDays($i)->setTime(0, 0, 0);
                //dd($datePast);
                $userRatePool->dailyProfit($userAssetPool, $datePast, true);
                //echo $datePast->format('Y-m-d'), '<br>';
            }
        }

        return [
            // 'asset_pool' => $userAssetPool,
            'user_updates_data' => $userUpdatesData,
        ];
    }

    public function updateAssetPool(array $request, UserAssetPool $userAssetPool) {

        $newPrice = (float)$request['price_usd'];
        $depositDiff = $newPrice - (float)$userAssetPool->price_usd;

        $commissionValueUsd = 0;
        if ($depositDiff) {
            $operationTypeComm = $depositDiff > 0 ? Commission::TYPE_ID_ASSET_POOL_BUY : Commission::TYPE_ID_ASSET_POOL_SELL;

            $commissionValueUsd = Commission::calculateCommission($operationTypeComm, $depositDiff);
            $operationTypeTr = $depositDiff > 0 ? Transaction::TYPE_ID_ASSET_POOL_BUY : Transaction::TYPE_ID_ASSET_POOL_SELL;
            $this->registerCommission($operationTypeTr, $userAssetPool->user_id, abs($depositDiff), $commissionValueUsd, $userAssetPool->asset_pool_id);
        }

        $userAssetPool->price_start_usd = $newPrice - $commissionValueUsd;
        $userAssetPool->price_usd = $newPrice - $commissionValueUsd;
        $userAssetPool->save();

        $userAssetPoolItems = (new UserAssetPoolItem())->initAllByUserPoolId($userAssetPool);

        // dd($userAssetPoolItems);
        $this->exchangeBotLog($userAssetPoolItems['diff'], 'update', $userAssetPool->user_id);

        //$poolData =
        $userAssetPool->setPoolAmount();

        $userUpdatesData = $this->userHistoryUpdates('U', $depositDiff - $commissionValueUsd, $userAssetPool->user_id, $userAssetPool->id, ['asset_pool_id' => $userAssetPool->asset_pool_id], $commissionValueUsd);

        return [
            //'pool_data' => $poolData,
            'user_updates_data' => $userUpdatesData,
        ];
    }

    public function updateRatePool(array $request, UserAssetPool $userAssetPool) {

        $newStartPrice = (float) $request['price_start'];

        $dateStart = $request['date_start'] ?? null;
        $dateEnd = $request['date_end'] ?? null;

        $ratePool = RatePool::with('currency')->find($userAssetPool->rate_pool_id);
        $currencyPriceUsd = $ratePool->currency->price_usd;

        $depositDiff = $newStartPrice - (float)$userAssetPool->price_start;
        $depositDiffUsd = $depositDiff/$currencyPriceUsd;

        $userAssetPool->price_start = $newStartPrice;
        $userAssetPool->price = $newStartPrice;
        $userAssetPool->price_start_usd = $userAssetPool->price_start/$currencyPriceUsd;
        $userAssetPool->price_usd = $userAssetPool->price/$currencyPriceUsd;
        if (isset($dateStart)) {
            $userAssetPool->rate_date_start = $dateStart;
        }
        if (isset($dateEnd)) {
            $userAssetPool->rate_date_end = $dateEnd;
        }
        $userAssetPool->save();

        $userAssetPool->setPoolAmount();

        $userUpdatesData = $this->userHistoryUpdates('U', $depositDiffUsd, $userAssetPool->user_id, $userAssetPool->id, ['rate_pool_id' => $userAssetPool->rate_pool_id]);
        //dd(123);

        return [
            //'pool_data' => $poolData,
            'user_updates_data' => $userUpdatesData,
        ];
    }

    public function deleteAssetPool(UserAssetPool $userAssetPool) {

        $userId = $userAssetPool->user_id;
        $userAssetPoolId = $userAssetPool->id;
        $assetPoolId = $userAssetPool->asset_pool_id;
        // $assetPoolStartPriceUsd = -1*floatval($userAssetPool->price_start_usd);
        $assetPoolPriceUsd = -1 * (float) $userAssetPool->price_usd;

        $userAssetPoolItems = (new UserAssetPoolItem())->deletePool($userAssetPool->id);

        $this->exchangeBotLog($userAssetPoolItems, 'delete', $userAssetPool->user_id);

        $userAssetPool->delete();

        $commissionValueUsd = Commission::calculateCommission(Commission::TYPE_ID_ASSET_POOL_BUY, $assetPoolPriceUsd);

        $this->registerCommission(Transaction::TYPE_ID_ASSET_POOL_SELL, $userId, abs($assetPoolPriceUsd), $commissionValueUsd, $assetPoolId);

        $this->userHistoryUpdates('D', $assetPoolPriceUsd, $userId, $userAssetPoolId, ['asset_pool_id' => $assetPoolId], $commissionValueUsd);

        // return true;
    }

    public function userHistoryUpdates($transactionType, $depositChangeUsd, $userId, $userAssetPoolId, array $poolId, float $commissionUsd = 0)
    {
        $userProfile = new UserProfile();
        $userBalanceHistory = new UserBalanceHistory();

        $assetPoolId = $poolId['asset_pool_id'] ?? 0;
        $ratePoolId = $poolId['rate_pool_id'] ?? 0;

        if ($depositChangeUsd) {
            $transactionType = strtoupper($transactionType);

            $userProfile = $userProfile->updateBalance($userId, [
                'deposit_change' => $depositChangeUsd
            ]);

            $userAssetPoolHistory = UserAssetPoolHistory::create([
                'transaction_type' => $transactionType,
                'user_id' => $userId,
                'asset_pool_id' => $assetPoolId,
                'rate_pool_id' => $ratePoolId,
                'user_asset_pool_id' => $transactionType != 'D' ? $userAssetPoolId : null,
                'balance_usd' => $userProfile->balance_usd,
                'deposit_usd' => $depositChangeUsd,
                'commission_usd' => $commissionUsd,
                'admin_id' => auth()->id() ?? 0,
            ]);

            $userBalanceHistory = $userBalanceHistory->commit($userProfile->toArray());
            // $userBalanceHistory = UserBalanceHistory::create([
            //     'user_id' => $userId,
            //     'balance_usd' => $userProfile->balance_usd,
            //     'deposits_usd' => $userProfile->deposits_usd - $userProfile->withdraws_usd
            // ]);

            return [
                'user_asset_pool_history' => $userAssetPoolHistory,
                'user_balance_history' => $userBalanceHistory,
                'user_profile' => $userProfile
            ];
        }

        return false;
    }

    public function registerCommission($typeId, $userId, $valueUsd, $commissionValueUsd, $poolId)
    {
        $poolPriceStartUsd = $valueUsd - $commissionValueUsd;

        $transaction = new Transaction();
        $transaction->commit([
            'type_id' => $typeId,
            'from_user_id' => $userId,
            'value' => $valueUsd,
            'commission_value' => $commissionValueUsd,
            'pool_id' => $poolId,
            'creator_user_id' => auth()->id() ?? 0
        ]);

        $exchangeInfo = new ExchangeInfo();
        $exchangeInfo->commit([
            'commission_usd' => $commissionValueUsd,
            'user_balance_usd' => $poolPriceStartUsd,
        ]);

        return [
            'value' => $valueUsd,
            'commission_value' => $commissionValueUsd,
        ];
    }

    function exchangeBotLog(array $userAssetPoolItemsDiff, $operationMode, int $userId) {
        // dd($userAssetPoolItemsDiff);
        $exchangeBotLog = new ExchangeBotLog();
        foreach ($userAssetPoolItemsDiff as $userAssetPoolItem) {
            $priceUsd = $userAssetPoolItem->price_usd;
            $priceUsdAbs = abs($priceUsd);
            $valueCryptoAbs = abs($userAssetPoolItem->asset_amount);
            if ($priceUsdAbs > 0) {
                $operationType = $priceUsd > 0 ? 'buy' : 'sell';
                $assetName = Asset::find($userAssetPoolItem->asset_pool_item_id)?->name ?? 'Error';
                if ($operationMode == 'create') {
                    $operationTypeId = ExchangeBotLog::OPERATION_TYPE_ID_ASSET_POOL_CREATE;
                } elseif ($operationMode == 'delete') {
                    $operationTypeId = ExchangeBotLog::OPERATION_TYPE_ID_ASSET_POOL_DELETE;
                } elseif ($operationMode == 'update') {
                    $operationTypeId = $operationType == 'buy' ? ExchangeBotLog::OPERATION_TYPE_ID_ASSET_POOL_BUY : ExchangeBotLog::OPERATION_TYPE_ID_ASSET_POOL_SELL;
                }
                $exchangeBotLog->commit([
                    'exchange_id' => ExchangeBotLog::EXCHANGE_ID_BINANCE,
                    'operation_type_id' => $operationTypeId,
                    'ex_operation_type_id' => ExchangeBotLog::EXCHANGE_OPERATION_TYPE_ID_EXCHANGE,
                    'asset_pool_item_id' => $userAssetPoolItem->asset_pool_item_id,
                    'user_id' => $userId,
                    'value_fiat' => $priceUsdAbs,
                    'value_crypto' => $valueCryptoAbs,
                    'api_request' => '> exchange ' . ($operationType === 'buy' ? "$priceUsdAbs USDT to $valueCryptoAbs $assetName" :  "$valueCryptoAbs $assetName to $priceUsdAbs USDT"),
                    'api_response' => '',
                    'request_ts' => microtime(true),
                    'response_ts' => 0,
                    'creator_user_id' => auth()->id() ?? 0
                ]);
            }
        }
    }
}
