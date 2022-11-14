<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExchangeBotLog extends Model
{
    use HasFactory, HasLogs;

    protected $table = 'exchange_bot_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'operation_type_id',
        'exchange_id',
        'ex_operation_type_id',
        'asset_pool_item_id',
        'user_id',
        'value_fiat',
        'value_crypto',
        'creator_user_id',
        'api_request',
        'api_response',
        'request_ts',
        'response_ts',
    ];

    public $timestamps = false;

    const EXCHANGE_ID_BINANCE = 1;

    const EXCHANGE_OPERATION_TYPE_ID_EXCHANGE = 1;

    const OPERATION_TYPE_ID_ASSET_POOL_CREATE = 1;
    const OPERATION_TYPE_ID_ASSET_POOL_BUY = 2;
    const OPERATION_TYPE_ID_ASSET_POOL_SELL = 3;
    const OPERATION_TYPE_ID_ASSET_POOL_DELETE = 4;
    const OPERATION_TYPE_ID_ASSET_POOL_BUY_AUTO = 6;
    const OPERATION_TYPE_ID_ASSET_POOL_SELL_AUTO = 7;

    static $oparationTypeData = [
        self::OPERATION_TYPE_ID_ASSET_POOL_CREATE => ['name' => 'Asset pool create', 'name_short' => 'AP Create'],
        self::OPERATION_TYPE_ID_ASSET_POOL_BUY => ['name' => 'Asset pool buy', 'name_short' => 'AP Buy'],
        self::OPERATION_TYPE_ID_ASSET_POOL_SELL => ['name' => 'Asset pool sell', 'name_short' => 'AP Sell'],
        self::OPERATION_TYPE_ID_ASSET_POOL_DELETE => ['name' => 'Asset pool delete', 'name_short' => 'AP Delete'],
        self::OPERATION_TYPE_ID_ASSET_POOL_BUY_AUTO => ['name' => 'Asset pool buy auto', 'name_short' => 'AP Buy Auto'],
        self::OPERATION_TYPE_ID_ASSET_POOL_SELL_AUTO => ['name' => 'Asset pool sell auti', 'name_short' => 'AP Sell Auto']
    ];

    public function getAll($params=[], $limits=[0, 100]) {

        $dataList = DB::table('exchange_bot_log as ebl')
                        ->leftJoin('users as u', 'u.id', '=', 'ebl.user_id')
                        ->leftJoin('users as cu', 'cu.id', '=', 'ebl.creator_user_id')
                        ->leftJoin('asset_pool_items as api', 'api.id', '=', 'ebl.asset_pool_item_id')
                        ->leftJoin('asset_pools as ap', 'ap.id', '=', 'api.asset_pool_id')
                        //->leftJoin('assets as a', 'a.id', '=', 'api.asset_id')
                        ->selectRaw('ebl.*, u.name as user_name, cu.name as creator_user_name, ap.name as asset_pool_name, ap.name_short as asset_pool_name_short,
                                        ap.id as asset_pool_id')
                        // ->whereIn('asset_pool_id', $poolsIds)
                        ->orderBy('ebl.id', 'DESC')
                        ->offset($limits[0])->limit($limits[1])
                        ->get();

        return $dataList;
    }

    public function commit($fields) {
        return self::create(
            [
                'exchange_id' => $fields['exchange_id'],
                'operation_type_id' => $fields['operation_type_id'],
                'ex_operation_type_id' => $fields['ex_operation_type_id'],
                'asset_pool_item_id' => $fields['asset_pool_item_id'],
                'user_id' => $fields['user_id'],
                'value_fiat' => $fields['value_fiat'],
                'value_crypto' => $fields['value_crypto'],
                'api_request' => $fields['api_request'],
                'api_response' => $fields['api_response'],
                'request_ts' => $fields['request_ts'],
                'response_ts' => $fields['response_ts'],
                'creator_user_id' => $fields['creator_user_id'],
            ]
        );
    }
}
