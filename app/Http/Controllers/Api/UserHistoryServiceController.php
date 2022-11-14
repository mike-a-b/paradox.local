<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UserAssetPoolHistory;
use App\Models\UserBalanceHistory;

class UserHistoryServiceController extends Controller
{
    /**
     * List of data
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'with_items' => 'integer',
            'offset' => 'integer|gt:-1',
            'count' => 'integer|gt:0',
            'date' => '',
        ]);
        $offset = $request->get('offset', 0);
        $count = $request->get('count', 10);
        $period = $request->get('date', 'ALL');

        $userId = auth()->id();

        $userAssetPoolHistory = new UserAssetPoolHistory();
        $userBalanceHistory = new UserBalanceHistory();

        // $historyRowsToShowCount = 10;

        $userId = auth()->user()->id;

        $apHistoryList = $userAssetPoolHistory->getAll($userId,$period);
        $apHistoryListPrint = $userAssetPoolHistory->prepareForPrint($apHistoryList);

        $apCommissionsHistoryList = $userAssetPoolHistory->fetchCommissionsList($apHistoryList);
        $apCommissionsHistoryListPrint = $userAssetPoolHistory->prepareForPrint($apCommissionsHistoryList);
        //dd($apCommissionsHistoryList->toArray(), $apHistoryList->toArray());
        //dd($apHistoryList->toArray());        

        $ubHistoryList = $userBalanceHistory->getCompressedHistory($userId, ['deposits_usdt', 'withdraws_usdt'],$period);
        $ubHistoryListPrint = $userBalanceHistory->prepareForPrint($ubHistoryList);

        $historyList = $ubHistoryListPrint
            ->concat($apHistoryListPrint)
            ->concat($apCommissionsHistoryListPrint)
            ->sortByDesc('created_at');

        $historyList = $historyList->slice($offset, $count);

        return [
            'data' => $historyList,
            'satatus' => 'success'
        ];
    }
}