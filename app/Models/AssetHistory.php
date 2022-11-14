<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AssetHistory extends Model
{
    use HasFactory, HasLogs;

    protected $table = 'asset_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_id',
        'price_usd',
        'market_cap',
        'cmc_rank',
        'source',
    ];

    public function getPoolsHistory(Carbon $date, $assetId=[]) {
        if (is_numeric($assetId)) {
             $assetId = [$assetId];
        }
        //dd($assetId);
        $history = self::whereIn('asset_id', $assetId)
                        ->orderBy('created_at', 'DESC')
                        ->offset(0)->limit(count($assetId))
                        ->get()->keyBy('asset_id');
        $historyOld = self::whereIn('asset_id', $assetId)
                        ->where('created_at', '<=', $date)
                        ->orderBy('created_at', 'DESC')
                        ->offset(0)->limit(count($assetId))
                        ->get()->keyBy('asset_id');
        //dd($history->toArray(), $historyOld->toArray());
        $ret = [];
        foreach ($history as $assetId => $data) {
            $newPrice = $data['price_usd'];
            $oldPrice = $historyOld[$assetId]['price_usd'] ?? $newPrice;
            //$v = round(100*abs($newPrice - $oldPrice)/$oldPrice, 2);
            //echo $assetId, ' ', $v,'<br>';
            $ret[$assetId] = [
                'new_price' => $newPrice,
                'old_price' => $oldPrice,
                'direction' => round($newPrice, 9) == round($oldPrice, 9) ? 'same' : ($newPrice > $oldPrice ? 'up' : 'down'),
                //'diff' => $newPrice - $oldPrice,
                'fraction' => $oldPrice > 0 && round($newPrice, 9) != round($oldPrice, 9) ? round(100*abs($newPrice - $oldPrice)/$oldPrice, 2) : 0,
            ];
        }
        //dd($ret);
        return $ret;
    }
}
