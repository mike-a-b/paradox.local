<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;
use App\Models\AssetPool;
use App\Models\Traits\HasLogs;

class AssetPoolItem extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_pool_id',
        'asset_id',
        'price_usd',
        'asset_amount',
        'fraction',
        'pos',
    ];

    protected $poolPriceUsd = 100;

    public function pool()
    {
        return $this->belongsTo(AssetPool::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function getAll($poolId=null) {
        //dd($poolId);
        $dataList = self::with('asset')->where(function($query) use ($poolId) {
            if (!empty($poolId)) {
                if (is_numeric($poolId)) {
                    $query->where('asset_pool_id', $poolId);
                } else {
                    $query->whereIn('asset_pool_id', $poolId);
                }
            }
        })->get()->map(function($item) {
            $item->deep = true;
            return $item;
        });

        if (!empty($dataList) && !is_numeric($poolId)) {
            $dataList = $dataList->groupBy('asset_pool_id');
        }

        return $dataList;
    }

    public function commit($fields, $mode='create') {
        //$priceUsd = $fields['price_usd'] ?? 0;
        $assetId = $fields['asset_id'];
        $fraction = $fields['fraction'];
        $assetPoolId = $fields['asset_pool_id'];

        $BC_BASE = config('app.bc_base');

        $asset = Asset::findOrFail($assetId);
        $assetPool = AssetPool::findOrFail($assetPoolId);

        // $priceUsd = ($fraction/$this->poolPriceUsd)*$assetPool->price_usd;
        // $assetAmount = $asset->price_usd > 0 ? $priceUsd/$asset->price_usd : 0;
        $priceUsd = bcmul(bcdiv($fraction, $this->poolPriceUsd, $BC_BASE), $assetPool->price_usd, $BC_BASE);
        $assetAmount = $asset->price_usd > 0 ? bcdiv($priceUsd, $asset->price_usd, $BC_BASE) : 0;

        if ($mode == 'create') {
            $pos = $this->where('asset_pool_id', $fields['asset_pool_id'])->max('pos') ?? 0;
            $pos++;
        } else {
            $pos = $fields['pos'];
        }

        return self::updateOrCreate(
            [
                'asset_pool_id' => $assetPoolId,
                'asset_id' => $assetId,
            ],
            [
                'fraction' => $fraction,
                'price_usd' => $priceUsd,
                'asset_amount' => $assetAmount,
                'pos' => $pos,
            ]
        );
    }

    // public static function calcPriceUsd($assetAmount, $assetRate) {
    //     return
    // }
}
