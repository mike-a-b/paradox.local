<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SiteHeaderInfo {
    private AssetHistory $assetsHistory;
    private Asset $assets;

    public function __construct() {
        $this->assets = new Asset();
        $this->assetsHistory = new AssetHistory();
    }

    public function getCryptoCurrencyInfo($assetIds = null, $pricePrecision = 2): Collection {
        if (empty($assetIds)) {
            $assetIds = [Asset::BTC_ID, Asset::ETH_ID];
        }

        $assetsList = $this->assets->whereIn('id', $assetIds)->orderByDesc('id')->get()->keyBy('id');
        $assetsHistory = $this->assetsHistory->getPoolsHistory(Carbon::now()->subHours(24), $assetIds);
        //dd($assetsList);

        $assetsHistory = collect($assetsHistory)->map(function ($item, $currId) use ($assetsList, $pricePrecision) {
            $item['new_price'] = number_format($item['new_price'], $pricePrecision);
            $item['symbol'] = $assetsList[$currId]->symbol;
            return $item;
        })->sortKeys();

        //dd($assetsHistory->toArray());

        return $assetsHistory;
    }

    public function getTotalMarketcap(): string {
        $sum = $this->assets->sum('market_cap');

        return number_format(round($sum / 1000000 / 1000));
    }

    public function getUsdtToUsdRate(): float {
        $priceUsd = $this->assets->find(Asset::USDT_ID)->price_usd;

        return (float)$priceUsd;
    }
}
