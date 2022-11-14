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

class IndexController extends Controller {
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view(
            'index'
        );
    }
}
