<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Asset;
use App\Models\AssetPoolItem;
use App\Models\UserAssetPool;
use App\Models\AssetPool;
use App\Models\Traits\HasLogs;

class UserAssetPoolItem extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_asset_pool_id',
        'asset_pool_item_id',
        'price_start_usd',
        'price_usd',
        'asset_amount'
    ];

    // public function pool()
    // {
    //     return $this->belongsTo(UserAssetPool::class);
    // }

    public function asset_pool_item()
    {
        return $this->belongsTo(AssetPoolItem::class);
    }

    public function getByUserPoolId($userPoolIds=[]) {
        if (is_numeric($userPoolIds)) {
            $userPoolIds = [$userPoolIds];
        }

        $dataList = DB::table('user_asset_pool_items as user_api')
                        ->leftJoin('asset_pool_items as api', 'api.id', '=', 'user_api.asset_pool_item_id')
                        ->leftJoin('assets as a', 'a.id', '=', 'api.asset_id')
                        ->selectRaw('user_api.*, api.fraction as asset_fraction, a.name as asset_name,
                                        a.symbol, a.slug as asset_slug, a.price_usd as asset_price_usd, a.logo, a.id as asset_id')
                        ->whereIn('user_api.user_asset_pool_id', $userPoolIds)
                        ->orderBy('fraction', 'DESC')
                        // ->where('mnemonics.user_id', auth()->user()->id)->where(function($query) use ($search) {
                        //     if (!empty($search)) {
                        //         $qs = "%$search%";
                        //         $query->where('mnemonics.mnemo', 'like', $qs)->orWhere('addresses.address', 'like', $qs);
                        //     }
                        // })->orderByRaw("$sortField ".(empty($sortOrder) ? 'ASC' : 'DESC').", mnemonics.id DESC")
                        // ->paginate($onPageCount);
                        ->get()->groupBy('user_asset_pool_id');

        //dd($dataList->toArray());

        return $dataList;
    }

    public function initAllByUserPoolId(UserAssetPool $userAssetPool) {
        $BC_BASE = config('app.bc_base');

        $assetPool = AssetPool::find($userAssetPool->asset_pool_id);

        $basePoolPrice = $assetPool->price_usd;

        $poolPriceStart = $userAssetPool->price_usd;

        // $keff = $poolPriceStart/$basePoolPrice;
        $assetPoolItems = AssetPoolItem::where('asset_pool_id', $userAssetPool->asset_pool_id)->get();
        $userAssetPoolItems = self::where('user_asset_pool_id', $userAssetPool->id)->get()->all();

        $keff = bcdiv($poolPriceStart, $basePoolPrice, $BC_BASE);
        $newUserAssetPoolItems = [];
        foreach ($assetPoolItems as $item) {
            // dd($item->toArray(),[
            //     'user_asset_pool_id' => $userAssetPool->id,
            //     'asset_pool_item_id' => $item->id,
            // ],
            // [
            //     'price_start_usd' => $keff*$item->price_usd,
            //     'price_usd' => $keff*$item->price_usd,
            //     'asset_amount' => $keff*$item->asset_amount,
            // ]);
            $newUserAssetPoolItems[] = self::updateOrCreate(
                [
                    'user_asset_pool_id' => $userAssetPool->id,
                    'asset_pool_item_id' => $item->id,
                ],
                [
                    // 'price_start_usd' => $keff*$item->price_usd,
                    // 'price_usd' => $keff*$item->price_usd,
                    // 'asset_amount' => $keff*$item->asset_amount,
                    'price_start_usd' => bcmul($keff, $item->price_usd, $BC_BASE),
                    'price_usd' => bcmul($keff, $item->price_usd, $BC_BASE),
                    'asset_amount' => bcmul($keff, $item->asset_amount, $BC_BASE),
                ]
            );
        }

        // dd($userAssetPoolItems->all(), $newUserAssetPoolItems);

        $diffUserAssetPoolItems = [];
        if (!empty($userAssetPoolItems)) {
            foreach ($userAssetPoolItems as $item) {
                $isDeleted = true;
                foreach ($newUserAssetPoolItems as $newItem) {
                    if ($item->asset_pool_item_id == $newItem->asset_pool_item_id) {
                        $newItem->price_start_usd = bcsub($newItem->price_start_usd, $item->price_start_usd, $BC_BASE);
                        $newItem->price_usd = bcsub($newItem->price_usd, $item->price_usd, $BC_BASE);
                        $newItem->asset_amount = bcsub($newItem->price_start_usd, $item->asset_amount, $BC_BASE);
                        $newItem->operation_type = 'update';
                        $diffUserAssetPoolItems[] = $newItem;
                        $isDeleted = false;
                        break;
                    }
                }
                if ($isDeleted) {
                    $item->operation_type = 'delete';
                    $diffUserAssetPoolItems[] = $item;
                }
            }
            foreach ($newUserAssetPoolItems as $newItem) {
                $isNew = true;
                foreach ($userAssetPoolItems as $item) {
                    if ($item->asset_pool_item_id === $newItem->asset_pool_item_id) {
                        $isNew = false;
                        break;
                    }
                }
                if ($isNew) {
                    $newItem->operation_type = 'create';
                    $diffUserAssetPoolItems[] = $newItem;
                }
            }
        } else {
            $diffUserAssetPoolItems = $newUserAssetPoolItems;
        }

         //dd($userAssetPoolItems, $newUserAssetPoolItems, $diffUserAssetPoolItems);

        $ret = [
            'diff' => $diffUserAssetPoolItems,
            'new' => $newUserAssetPoolItems,
            'old' => $userAssetPoolItems,
        ];

        $ret = array_map(function($arr) {
            $arr = array_map(function($item) {
                return (object)$item->toArray();
            }, $arr);
            return $arr;
        }, $ret);

        //dd($ret);

        return $ret;
    }

    public function commit($fields, $mode='create') {
        //$priceUsd = $fields['price_usd'] ?? 0;
        $assetId = $fields['asset_id'];
        $fraction = $fields['fraction'];

        $BC_BASE = config('app.bc_base');

        $asset = Asset::find($assetId);

        // $priceUsd = ($fraction/$this->poolPriceUsd)*100;
        $priceUsd = bcdiv($fraction, $this->poolPriceUsd, $BC_BASE)*100;
        // $assetAmount = $priceUsd/$asset->price_usd;
        $assetAmount = bcdiv($priceUsd, $asset->price_usd, $BC_BASE);

        if ($mode == 'create') {
            $pos = $this->where('asset_pool_id', $fields['asset_pool_id'])->max('pos') ?? 0;
            $pos++;
        } else {
            $pos = $fields['pos'];
        }

        return self::updateOrCreate(
            [
                'asset_pool_id' => $fields['asset_pool_id'],
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

    public function deletePool($userAssetPoolId) {

        $selfItems = self::where('user_asset_pool_id', $userAssetPoolId)->get();
        //dd($assetPoolItems->toArray());
        $userAssetPoolItems = [];
        foreach ($selfItems as $item) {
            $userAssetPoolItems[] = clone $item;
            $item->delete();
        }

        return $userAssetPoolItems;
    }
}
