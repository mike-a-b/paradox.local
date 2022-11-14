<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'currency_id',
        'from_user_id',
        'to_user_id',
        'value',
        'commission_value',
        'pool_id',
        'creator_user_id',
    ];

    const TYPE_ID_ASSET_POOL_BUY = 1;
    const TYPE_ID_ASSET_POOL_SELL = 2;

    static $typeData = [
        self::TYPE_ID_ASSET_POOL_BUY => ['name' => 'Asset pool buy', 'name_short' => 'AP Buy'],
        self::TYPE_ID_ASSET_POOL_SELL => ['name' => 'Asset pool sell', 'name_short' => 'AP Sell'],
    ];

    public function getAll($params=[], $limits=[0, 100]) {

        $dataList = DB::table('transactions as tr')
                        ->leftJoin('users as u', 'u.id', '=', 'tr.from_user_id')
                        ->leftJoin('users as cu', 'cu.id', '=', 'tr.creator_user_id')
                        ->leftJoin('asset_pools as ap', 'ap.id', '=', 'tr.pool_id')
                        ->selectRaw('tr.*, u.name as from_user_name, cu.name as creator_user_name, ap.name as asset_pool_name, ap.name_short as asset_pool_name_short')
                        // ->whereIn('asset_pool_id', $poolsIds)
                        ->orderBy('tr.id', 'DESC')
                        ->offset($limits[0])->limit($limits[1])
                        ->get();

        // $dataList = $dataList->map(function ($item) use ($dataRightList) {
        //     //dd($item->toArray());
        //     $item->assets = $dataRightList[$item->id] ?? [];

        //     return $item;
        // });
        //$dataList = $dataList->sortBy('group.pos');
        // $dataList = $dataList->sortBy([
        //                 ['group.pos', 'asc'],
        //                 ['is_active', 'desc'],
        //                 ['price_usd', 'desc'],
        //             ]);
        //dd($dataList->toArray());

        return $dataList;
        // $ret = self::query()
        //             ->when(!empty($params['query']), function($query) use ($params) {
        //                 $query->where('name', 'like', "{$params['query']}%");
        //             })
        //             ->orderBy('pos', 'ASC')
        //             ->offset($limits[0])->limit($limits[1])
        //             ->get();
        // return $ret;
    }

    public function commit($fields) {
        return self::create(
            [
                'type_id' => $fields['type_id'],
                'currency_id' => $fields['currency_id'] ?? 0,
                'from_user_id' => $fields['from_user_id'] ?? 0,
                'to_user_id' => $fields['to_user_id'] ?? 0,
                'value' => $fields['value'],
                'commission_value' => $fields['commission_value'] ?? 0,
                'pool_id' => $fields['pool_id'],
                'creator_user_id' => $fields['creator_user_id'],
            ]
        );
    }
}
