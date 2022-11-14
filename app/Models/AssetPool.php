<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AssetPoolItem;
use App\Models\AssetPoolGroup;
use App\Models\Traits\HasLogs;
use Carbon\Carbon;

class AssetPool extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_short',
        'description',
        'description_ru',
        'logo',
        'price_start_usd',
        'price_usd',
        'asset_type_id',
        'asset_pool_group_id',
        'asset_pool_template_id',
        'meta',
        'rebalance_frequency',
        'rebalanced_at',
        'is_topmarketcap_based',
        'is_active',
        'pos',
    ];

    public function items()
    {
        return $this->hasMany(AssetPoolItem::class);
    }
    public function group()
    {
        return $this->hasOne(AssetPoolGroup::class, 'id', 'asset_pool_group_id');
    }

    public function getAll($id=0, $isActive=-1) {

        $dataList = self::with('group')
                        ->when(!empty($id), function($query) use ($id) {
                            $query->where('id', $id);
                        })
                        ->when($isActive!=-1, function($query) use ($isActive) {
                            $query->where('is_active', $isActive);
                        })
                        //->orderBy('asset_pool_group_id', 'DESC')
                        ->get();

        $poolsIds = $dataList->pluck('id');

        //dd($dataList->toArray());

        $dataRightList = DB::table('asset_pool_items')
                        ->leftJoin('assets', 'assets.id', '=', 'asset_pool_items.asset_id')
                        ->selectRaw('asset_pool_id, asset_amount, asset_pool_items.fraction, asset_pool_items.price_usd as pool_item_price_usd, assets.name as asset_name,
                                        assets.symbol, assets.slug as asset_slug, assets.price_usd as asset_price_usd, assets.logo, assets.id as asset_id')
                        ->whereIn('asset_pool_id', $poolsIds)
                        ->orderBy('fraction', 'DESC')
                        // })->orderByRaw("$sortField ".(empty($sortOrder) ? 'ASC' : 'DESC').", mnemonics.id DESC")
                        // ->paginate($onPageCount);
                        ->get()->groupBy('asset_pool_id');

        $dataList = $dataList->map(function ($item) use ($dataRightList) {
            //dd($item->toArray());
            $item->assets = $dataRightList[$item->id] ?? [];

            return $item;
        });

        $dataList = $dataList->sortBy([
                        ['group.pos', 'asc'],
                        ['is_active', 'desc'],
                        ['price_usd', 'desc'],
                    ]);
        //dd($dataList->toArray());

        return $dataList;
    }

    public function commit($fields, $mode='create') {
        if ($mode == 'create') {
            $pos = $this->max('pos') ?? 0;
            $priceUsd = $fields['price_usd'] ?? 0;
            $pos++;
        } else {
            $pos = $fields['pos'];
            $priceUsd = $fields['price_usd'] ?? AssetPoolItem::where('asset_pool_id', $fields['id'])->sum('price_usd');
            $where = ['id' => $fields['id']];
        }

        $isTopmarketcapBased = empty($fields['is_topmarketcap_based']) ? 0 : 1;
        $assetPoolTemplateId = $fields['asset_pool_template_id'] ?? 0;
        $rebalanceFrequency = $fields['rebalance_frequency'] ?? 0;
        $meta = $fields['meta'] ?? null;
        if (!$isTopmarketcapBased && empty($meta)) {
            $assetPoolTemplateId = 0;
            $rebalanceFrequency = 0;
        }

        $data = [
            'asset_type_id' => $fields['asset_type_id'],
            'name' => $fields['name'],
            'name_short' => $fields['name_short'],
            'description' => $fields['description'],
            'description_ru' => $fields['description_ru'],
            'price_usd' => $priceUsd,
            'asset_pool_group_id' => $fields['asset_pool_group_id'],
            'asset_pool_template_id' => $assetPoolTemplateId,
            'rebalance_frequency' => $rebalanceFrequency,
            'rebalanced_at' => $fields['rebalanced_at'] ?? null,
            'meta' => $meta,
            'pos' => $pos,
            'is_topmarketcap_based' => empty($fields['is_topmarketcap_based']) ? 0 : 1,
            'is_active' => empty($fields['is_active']) ? 0 : 1
        ];
        if (isset($fields['price_start_usd'])) {
            $data['price_start_usd'] = $fields['price_start_usd'];
        }
        if (!empty($fields['logo'])) {
            $data['logo'] = $fields['logo'];
        }
        if ($mode == 'create') {
            $where = $data;
        }
        //dd($pos, $fields);
        return self::updateOrCreate(
            $where,
            $data
        );
    }

    public function getRebalanceDateNext($rebalancedAt, int $rebalanceFrequency)
    {
        if ($rebalancedAt && $rebalanceFrequency) {
            $ret = Carbon::parse($rebalancedAt)->addDays($rebalanceFrequency)->format('Y-m-d 24:00:00');
        } else {
            $ret = Carbon::now()->format('Y-m-d 24:00:00');
        }

        return $ret;
    }
}
