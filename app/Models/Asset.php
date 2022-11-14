<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\DB;
//use App\Jobs\MnemoToAddress;

class Asset extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coinmarketcap_id',
        'asset_type_id',
        'name',
        'symbol',
        'price_usd',
        'market_cap',
        'cmc_rank',
        'logo',
        'slug',
        'is_stoplisted',
        'pos',
    ];

    //public $timestamps = false;

    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    const BTC_ID = 1;
    const ETH_ID = 2;
    const USDT_ID = 3;

    public function assetType()
    {
        return $this->hasOne(AssetType::class);
    }

    public function poolItem()
    {
        return $this->hasOne(AssetPoolItem::class);
    }

    public function getAll($params=[], $limits=[0, 100]) {
        $ret = self::query()
                    ->when(!empty($params['query']), function($query) use ($params) {
                        $query->where('name', 'like', "{$params['query']}%")
                              ->orWhere('symbol', 'like', "{$params['query']}%");
                    })
                    ->when(isset($params['is_stoplisted']), function($query) use ($params) {
                        $query->where('is_stoplisted', $params['is_stoplisted'] ? 1 : 0);
                    })
                    ->orderBy('id', 'ASC')
                    ->offset($limits[0])->limit($limits[1])
                    ->get();
        return $ret;
    }

    public function insert($fields) {
        $pos = $this->max('pos') ?? 0;
        return self::updateOrCreate(
            [
                'asset_type_id' => $fields['asset_type_id'],
                'coinmarketcap_id' => $fields['coinmarketcap_id'],
            ],
            [
                'name' => $fields['name'],
                'symbol' => $fields['symbol'],

                'price_usd' => $fields['price_usd'] ?? 0,
                'market_cap' => $fields['market_cap'] ?? 0,
                'cmc_rank' => $fields['cmc_rank'] ?? 0,
                'logo' => $fields['logo'] ?? '',
                'slug' => $fields['slug'] ?? '',
                'pos' => $pos + 1
            ]
        );
    }
}
