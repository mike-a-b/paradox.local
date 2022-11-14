<?php

namespace App\Providers;

use App\Services\BladeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Services\SiteHeaderInfo;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        // View::share('key', 'value');
        //
        // dd($userInfo);

        View::composer('*', static function ($view) {

            $siteHeaderInfo = new SiteHeaderInfo();
            //dd($siteHeaderInfo->getUsdtToUsdRate());
            $userInfo = Auth::user();
            if (isset($userInfo->profile)) {
                $userInfo->profile->balance_sum_usd = $userInfo->profile->balance_usd + $siteHeaderInfo->getUsdtToUsdRate() * $userInfo->profile->balance_usdt;
            }
            $userBalance_usd = 0;
            $userBalance_usdt= 0;
            if($userInfo){
                $userData = DB::table('users as u')
                    ->leftJoin('user_profiles as up', 'u.id', '=', 'up.user_id')
                    ->selectRaw('u.*, up.balance_usd, up.balance_usdt')
                    ->where('u.id', $userInfo->id)->first();

                if ($userData){
                    $userBalance_usd = $userData->balance_usd;
                    $userBalance_usdt = $userData->balance_usdt;
                }
            }

            $bladeService = new BladeService();
            $userBalance_usd = $bladeService->printPriceBig($userBalance_usd);
            $userBalance_usdt = $bladeService->printPriceBig($userBalance_usdt);
            //$userInfo->profile = $userInfo->profile;
            //$userInfo = (object)$userInfo->toArray();
            //dd($userInfo);
            $view->with('authUser', $userInfo);
            $view->with('userBalance_usd', $userBalance_usd);
            $view->with('userBalance_usdt', $userBalance_usdt);

            $headerInfo = (object)[
                'cryptocurrency_info' => $siteHeaderInfo->getCryptoCurrencyInfo(),
                'marketcup_total' => $siteHeaderInfo->getTotalMarketcap(),
            ];
            //dd($headerInfo);
            $view->with('headerInfo', $headerInfo);
            $view->with('currentLocale', App::getLocale());
        });

        Paginator::useBootstrap();
    }
}
