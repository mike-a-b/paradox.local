<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\UserAssetPoolItem;
use App\Models\AssetPool;
use App\Models\RatePool;
use App\Models\AssetPoolGroup;
use App\Models\Traits\HasLogs;

class UserAssetPool extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'asset_pool_id',
        'rate_pool_id',
        'asset_pool_amount',
        'price_start_usd',
        'price_usd',
        'price_start',
        'price',
        'rate_date_start',
        'rate_date_end',
        'created_at'
    ];

    public function items()
    {
        return $this->hasMany(UserAssetPoolItem::class);
    }

    public function asset_pool()
    {
        return $this->hasOne(AssetPool::class, 'id', 'asset_pool_id');
    }

    public function rate_pool()
    {
        return $this->hasOne(RatePool::class, 'id', 'rate_pool_id');
    }

    public function getByUserId($userId, $poolType, $poolId=0) {
        //dd($sortField, $sortOrder);

        $dataList = self::where('user_id', $userId)
                        ->when($poolType == AssetPoolGroup::G_TYPE_ASSET_POOL, function ($query) use ($poolId) {
                            $query->with('asset_pool')
                                  ->where(function ($query) use ($poolId) {
                                      if ($poolId) {
                                          $query->where('asset_pool_id', $poolId);
                                      } else {
                                          $query->where('asset_pool_id', '>', 0);
                                      }
                                  });
                        })
                        ->when($poolType == AssetPoolGroup::G_TYPE_RATE_POOL, function ($query) use ($poolId)  {
                            $query->with('rate_pool')
                                  ->where(function ($query) use ($poolId) {
                                      if ($poolId) {
                                          $query->where('rate_pool_id', $poolId);
                                      } else {
                                          $query->where('rate_pool_id', '>', 0);
                                      }
                                  });
                        })
                        ->orderByDesc('price_usd')
                        ->get();


        //$poolsIds = $dataList->pluck('id');

        //echo '<pre>';
        //print_r($dataList->toArray());

        return $dataList;
    }

    public function setPoolAmount($userAssetPoolId=null) {
        $BC_BASE = config('app.bc_base');
        $pool = $userAssetPoolId ? $this->find($userAssetPoolId) : $this;
        $assetPool = null;
        if ($pool->asset_pool_id) {
            $assetPool = AssetPool::find($pool->asset_pool_id);
        } // elseif ($pool->rate_pool_id) {
            //$assetPool = RatePool::find($pool->rate_pool_id);
        //}
        //dd($assetPool->toArray(), $pool->toArray());
        if ($assetPool) {
            // $pool->asset_pool_amount = $pool->price_start_usd/$assetPool->price_start_usd;
            // $pool->asset_pool_amount = $pool->price_usd/$assetPool->price_usd;
            $pool->asset_pool_amount = bcdiv($pool->price_usd, $assetPool->price_usd, $BC_BASE);
            $pool->save();
        }

        return $pool;
    }
}
