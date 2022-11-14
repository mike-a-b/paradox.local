<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetPoolHistory;
use App\Http\Resources\AssetPoolHistoryResource;
use App\Http\Resources\AssetPoolHistoryCollection;
use Carbon\Carbon;

class AssetPoolHistoryController extends Controller
{    
    protected function validatePeriod(?string $period) : string
    {
        $ret = in_array($period, ['1h', '', '7d', 'm', '3m', '1y', 'all']) ? $period : '';

        return $ret;
    }

    protected function dateFromByPeriod(?string $period) : ?Carbon
    {
        $dateFrom = null;        
        if ($period == '1h') {
            $dateFrom = Carbon::now()->subMinutes(60);
        } elseif ($period == '') {
            $dateFrom = Carbon::now()->subHours(24);
        } elseif ($period == '7d') {
            $dateFrom = Carbon::now()->subDays(7);
        } elseif ($period == 'm') {
            $dateFrom = Carbon::now()->subDays(30);
        } elseif ($period == '3m') {
            $dateFrom = Carbon::now()->subDays(90);
        } elseif ($period == '1y') {
            $dateFrom = Carbon::now()->subDays(365);
        } elseif ($period == '1y') {
            $dateFrom = Carbon::now()->subDays(365);
        } 
        // elseif ($period == 'all') {
        //     $dateFrom = null;
        // }

        return $dateFrom;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, AssetPoolHistory $assetPoolHistory)
    {        
        $request->validate([            
            'pool_id' => 'required|integer',
            'period' => 'required|string',
        ]);

        $poolId = $request->get('pool_id');        
        $period = $request->get('period');        
        
        $period = $this->validatePeriod($period);
        $dateFrom = $this->dateFromByPeriod($period);
        
        $list = $assetPoolHistory::where('asset_pool_id', $poolId)
                                    ->when($dateFrom, function($q) use ($dateFrom) {
                                        $q->where('created_at', '>=', $dateFrom);
                                    })
                                    ->orderBy('created_at', 'ASC')
                                    // ->toSql();
                                    // dd($list, $dateFrom, $poolId);
                                    ->get();
        //dd($list->toArray());

        return new AssetPoolHistoryCollection($list);
    }

    public function statistics(Request $request, AssetPoolHistory $assetPoolHistory)
    {        
        $request->validate([            
            'pool_id' => 'required|integer',
            'period' => 'required|string',
        ]);

        $poolId = $request->get('pool_id');        
        $period = $request->get('period');        
        
        $period = $this->validatePeriod($period);
        $dateFrom = $this->dateFromByPeriod($period);
                        
        $poolHistory = $assetPoolHistory->getPoolHistory($dateFrom, $poolId);    
        //dd($poolHistory);
        
        $poolLastHistory = empty($poolHistory) ? [] : array_shift($poolHistory);
       
        //dd($poolLastHistory);

        return [
            'data' => $poolLastHistory
        ];
    }
}