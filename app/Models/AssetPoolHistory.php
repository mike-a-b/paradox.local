<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use App\Models\AssetHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AssetPoolHistory extends Model
{
    use HasFactory, HasLogs;

    protected $table = 'asset_pool_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_pool_id',
        'price_usd',
    ];

    public function getPoolStartPoint(int $poolId) {
        $ret = $this->where('asset_pool_id', $poolId)->orderBy('created_at', 'ASC')->first();

        return $ret;
    }

    public function getPoolHistory(?Carbon $date, $poolId=[]) {
        $history = [];
        $historyOld = [];

        if (is_numeric($poolId)) {
            $poolId = [$poolId];
            $history = self::whereIn('asset_pool_id', $poolId)
                        ->orderBy('created_at', 'DESC')
                        ->offset(0)->limit(1)
                        ->get()->keyBy('asset_pool_id');
            $historyOld = self::whereIn('asset_pool_id', $poolId)
                            ->when($date,
                                function ($q) use ($date) {
                                    $q->where('created_at', '<=', $date)
                                        ->orderBy('created_at', 'DESC');
                                },
                                function ($q) {
                                    $q->orderBy('created_at', 'ASC');
                                }
                            )
                            ->offset(0)->limit(1)
                            ->get()->keyBy('asset_pool_id');
        } else {
            $historyMaxDate = self::whereIn('asset_pool_id', $poolId)
                        ->select(DB::raw('max(created_at) as created_at'), 'asset_pool_id')
                        ->groupBy('asset_pool_id')
                        ->get()
                        ->keyBy('asset_pool_id');
            $historyOldMaxDate = self::whereIn('asset_pool_id', $poolId)
                        ->when($date, function ($q) use ($date) {
                            $q->where('created_at', '<=', $date);
                        })
                        ->select(DB::raw('max(created_at) as created_at'), 'asset_pool_id')
                        ->groupBy('asset_pool_id')
                        ->get();

            if ($historyMaxDate->first()) {
                $history = self::where(function($query) use ($historyMaxDate) {
                                    if (!empty($historyMaxDate)) {
                                        foreach($historyMaxDate as $hd) {
                                            $query->orWhere(function($queryIn) use ($hd) {
                                                $queryIn->where('created_at', $hd->created_at)->where('asset_pool_id', $hd->asset_pool_id);
                                            });
                                        }
                                    }
                                })
                                ->get()
                                ->keyBy('asset_pool_id');
            }
            if ($historyOldMaxDate->first()) {
                $historyOld = self::where(function($query) use ($historyOldMaxDate) {
                                        if (!empty($historyOldMaxDate)) {
                                            foreach($historyOldMaxDate as $hd) {
                                                $query->orWhere(function($queryIn) use ($hd) {
                                                    $queryIn->where('created_at', $hd->created_at)->where('asset_pool_id', $hd->asset_pool_id);
                                                });
                                            }
                                        }
                                    })
                                    ->get()
                                    ->keyBy('asset_pool_id');
            }

        }

        $ret = [];
        foreach ($history as $assetId => $data) {
            $newPrice = $data['price_usd'];
            $oldPrice = $historyOld[$assetId]['price_usd'] ?? $newPrice;
            $ret[$assetId] = [
                'new_price' => $newPrice,
                'old_price' => $oldPrice,
                // 'direction' => round($newPrice, 4) == round($oldPrice, 4) ? 'same' : ($newPrice > $oldPrice ? 'up' : 'down'),
                //'diff' => $newPrice - $oldPrice,
                // 'fraction' => $oldPrice && round($newPrice, 4) != round($oldPrice, 4) ? round(100*abs($newPrice - $oldPrice)/$oldPrice, 2) : 0,
            ] + $this->calculateAverageFraction($assetId);
        }

        return $ret;
    }

    private function calculateAverageFraction($id) : array
    {
        $result = [
            'direction' => 'down',
            'fraction' => 0
        ];

        $assetPool = AssetPool::find($id)?->items->pluck('asset_id');

        if ($assetPool) {
            $poolPercentHistory = array_map(function($item) {
                return $item['fraction'] * ($item['direction'] === 'up' ? 1 : -1);
            }, (new AssetHistory)->getPoolsHistory(Carbon::now()->subHours(24), $assetPool));

            $poolPercentHistory = array_values($poolPercentHistory);
            $averageValue = array_sum($poolPercentHistory) / count($poolPercentHistory);

            $result = [
                'direction' => $averageValue > 0 ? "up" : "down",
                'fraction' => round(abs($averageValue), 2)
            ];
        }

        return $result;
    }
}
