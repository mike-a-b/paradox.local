<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAssetPoolHistory;
use App\Models\UserBalanceHistory;


class HistoryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(UserAssetPoolHistory $userAssetPoolHistory, UserBalanceHistory $userBalanceHistory)
    {
        $historyRowsToShowCount = 10;

        $userId = auth()->user()->id;

        $apHistoryList = $userAssetPoolHistory->getAll($userId, '1yar');
        $apHistoryListPrint = $userAssetPoolHistory->prepareForPrint($apHistoryList);

        $apCommissionsHistoryList = $userAssetPoolHistory->fetchCommissionsList($apHistoryList);
        $apCommissionsHistoryListPrint = $userAssetPoolHistory->prepareForPrint($apCommissionsHistoryList);

        $ubHistoryList = $userBalanceHistory->getCompressedHistory($userId, ['deposits_usdt', 'withdraws_usdt'], '1yar');
        $ubHistoryListPrint = $userBalanceHistory->prepareForPrint($ubHistoryList);

        $historyList = $ubHistoryListPrint
                            ->concat($apHistoryListPrint)
                            ->concat($apCommissionsHistoryListPrint)
                            ->sortByDesc('created_at');

        return view('history.index', compact('historyList', 'historyRowsToShowCount'));
    }
}
