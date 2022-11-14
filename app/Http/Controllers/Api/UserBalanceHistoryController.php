<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserBalanceHistory;
use App\Http\Resources\UserBalanceHistoryResource;
use App\Http\Resources\UserBalanceHistoryCollection;
use Carbon\Carbon;

class UserBalanceHistoryController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    protected function validateUser(?int $userId): int
    {
        $authUser = auth()->user();
        if ($authUser->isAdmin()) {
            if (!$userId) {
                $userId = $authUser->id;
            }
        } else {
            if (!$userId) {
                $userId = $authUser->id;
            } else {
                abort(403);
            }
        }

        return $userId;
    }

    protected function validatePeriod(?string $period): string
    {
        $ret = in_array($period, ['1h', '24h', '7d', 'm', '3m', '1y', 'all'], true) ? $period : '';

        return $ret;
    }

    protected function dateFromByPeriod(?string $period): ?Carbon
    {
        $dateFrom = null;
        if ($period == '1h') {
            $dateFrom = Carbon::now()->subMinutes(60);
        } elseif ($period == '24h') {
            $dateFrom = Carbon::now()->subHours(24);
        } elseif ($period == '7d') {
            $dateFrom = Carbon::now()->subDays(7);
        } elseif ($period == 'm') {
            $dateFrom = Carbon::now()->subDays(30);
        } elseif ($period == '3m') {
            $dateFrom = Carbon::now()->subDays(90);
        } elseif ($period == '1y') {
            $dateFrom = Carbon::now()->subDays(365);
        }

        return $dateFrom;
    }

    public function index(Request $request, UserBalanceHistory $userBalanceHistory)
    {
        $request->validate([
            'user_id' => 'integer',
            'period' => 'required|string',
        ]);

        $userId = $request->get('user_id');
        $period = $request->get('period');

        $userId = $this->validateUser($userId);
        $period = $this->validatePeriod($period);
        $dateFrom = $this->dateFromByPeriod($period);

        $list = $userBalanceHistory::where('user_id', $userId)
            ->when($dateFrom, function ($q) use ($dateFrom) {
                $q->where('created_at', '>=', $dateFrom);
            })
            ->orderBy('created_at', 'ASC')
            ->get();

        //dd($list->toArray());

        return new UserBalanceHistoryCollection($list);
    }

    public function statistics(Request $request, UserBalanceHistory $userBalanceHistory): array
    {
        $request->validate([
            'user_id' => 'integer',
            'period' => 'required|string',
        ]);

        $userId = $request->get('user_id');
        $period = $request->get('period');

        $userId = $this->validateUser($userId);
        $period = $this->validatePeriod($period);
        $dateFrom = $this->dateFromByPeriod($period);

        $isShortTerm = !in_array($period, ['1h','24h','7d', 'm', '3m', '1y', 'all']);

        $userBalanceLastInfo = $userBalanceHistory->where('user_id', $userId)
            ->when($dateFrom, function ($q) use ($dateFrom) {
                $q->where('created_at', '>=', $dateFrom);
            })
            ->orderBy('created_at', 'ASC')
            ->first();

        $userBalanceDiffUsd = 0;
        $userBalanceDiffPers = 0;
        // if ($userBalanceLastInfo) {
        //     if ($isShortTerm) {
        //         $userBalanceDiffUsd = $userBalanceLastInfo->balance_usd - ($userBalanceLastInfo->deposits_usd - $userBalanceLastInfo->withdraws_usd);
        //         $userBalanceDiffPers = $userBalanceDiffUsd ? round(100*$userBalanceDiffUsd/($userBalanceLastInfo->deposits_usd - $userBalanceLastInfo->withdraws_usd), 2) : round($userBalanceDiffUsd/100, 2);
        //         $userBalanceDiffUsd = number_format(abs($userBalanceDiffUsd), 2);
        //     } else {
        //         $userBalanceFinalLastInfo = $userBalanceHistory->where('user_id', $userId)->latest('created_at')->first();
        //         if ($userBalanceFinalLastInfo) {
        //             $userBalanceDiffUsd = $userBalanceFinalLastInfo->balance_usd - $userBalanceLastInfo->deposits_usd;
        //             $userBalanceDiffPers = $userBalanceDiffUsd ? round(100*$userBalanceDiffUsd/($userBalanceLastInfo->deposits_usd - $userBalanceLastInfo->withdraws_usd), 2) : round($userBalanceDiffUsd/100, 2);
        //             $userBalanceDiffUsd = number_format(abs($userBalanceDiffUsd), 2);
        //             //dd($userBalanceLastInfo->toArray(), $userBalanceFinalLastInfo->toArray());
        //         }
        //     }
        // }
        if ($userBalanceLastInfo) {
            $rateUstd = $userBalanceLastInfo->rate_ustd_to_usd;
            $depWithdrawLastSumUsd = ($userBalanceLastInfo->deposits_usd - $userBalanceLastInfo->withdraws_usd)
                + $rateUstd * ($userBalanceLastInfo->deposits_usdt - $userBalanceLastInfo->withdraws_usdt);
            if ($isShortTerm) {
                $userBalanceDiffUsd = $userBalanceLastInfo->user->profile->balance_usd - $userBalanceLastInfo->balance_usd;
                $userBalanceDiffPers = $userBalanceDiffUsd ? round(100 * $userBalanceDiffUsd / $depWithdrawLastSumUsd, 2) : round($userBalanceDiffUsd / 100, 2);
                $userBalanceDiffUsd = number_format(abs($userBalanceDiffUsd), 2);
            } else {
                $userBalanceFinalLastInfo = $userBalanceHistory->where('user_id', $userId)->latest('created_at')->first();
                if ($userBalanceFinalLastInfo) {
                    $userBalanceDiffUsd = $userBalanceFinalLastInfo->balance_usd + $userBalanceFinalLastInfo->rate_ustd_to_usd * $userBalanceLastInfo->balance_usdt
                        - ($userBalanceLastInfo->deposits_usd + $rateUstd * $userBalanceLastInfo->deposits_usdt);
                    $userBalanceDiffPers = $userBalanceDiffUsd ? round(100 * $userBalanceDiffUsd / $depWithdrawLastSumUsd, 2) : round($userBalanceDiffUsd / 100, 2);
                    $userBalanceDiffUsd = number_format(abs($userBalanceDiffUsd), 2);
                    //dd($userBalanceLastInfo->toArray(), $userBalanceFinalLastInfo->toArray());
                }
            }
        }

        return [
            'data' => [
                'user_balance_diff_pers' => $userBalanceDiffPers,
                //'user_balance_diff_pers_print' => $userBalanceDiffPers_print,
                'user_balance_diff_usd' => $userBalanceDiffUsd,
            ],
        ];
    }
}
