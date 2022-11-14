<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// use App\Models\UserAssetPoolItem;
// use App\Models\AssetPool;

class UserAssetPoolHistory extends Model
{
    use HasFactory, HasLogs;

    protected $table = 'user_asset_pool_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_type',
        'user_id',
        'asset_pool_id',
        'rate_pool_id',
        'user_asset_pool_id',
        'balance_usd',
        'deposit_usd',
        'commission_usd',
        'admin_id',
        'created_at'
    ];

    const TRANSACTION_TYPE_PERCENTAGE = 'P';

    public static $transactionTypes = [
        'D' => ['name' => 'Delete', 'name_tr' => 'Sell'],
        'C' => ['name' => 'Create', 'name_tr' => 'Buy'],
        'U' => ['name' => 'Update', 'name_tr' => 'Update'],
        self::TRANSACTION_TYPE_PERCENTAGE => ['name' => 'Percentage', 'name_tr' => 'Percentage'],
        'M' => ['name' => 'Commission', 'name_tr' => 'Commission'],
    ];

    public function getAll(int $userId, $period)
    {
        $dataList = DB::table('user_asset_pool_history as uap_history')
            ->leftJoin('user_asset_pools as ua_pools', 'ua_pools.id', '=', 'uap_history.user_asset_pool_id')
            ->leftJoin('asset_pools as a_pools', 'a_pools.id', '=', 'uap_history.asset_pool_id')
            ->leftJoin('rate_pools as r_pools', 'r_pools.id', '=', 'uap_history.rate_pool_id')
            ->selectRaw('uap_history.*,
                                     a_pools.name as pool_name, a_pools.logo as pool_logo, a_pools.id as pool_id,
                                     r_pools.name as r_pool_name, r_pools.logo as r_pool_logo, r_pools.id as r_pool_id'
            )
            // ->where(function ($query) use ($period) {
            //     if ($period == '24hours') {
            //         $query->where('uap_history.created_at', '>=', Carbon::now()->subHours(24));
            //     } elseif ($period == '7days') {
            //         $query->where('uap_history.created_at', '>=', Carbon::now()->subDays(7));
            //     } elseif ($period == '30days') {
            //         $query->where('uap_history.created_at', '>=', Carbon::now()->subDays(30));
            //     } elseif ($period == '90days') {
            //         $query->where('uap_history.created_at', '>=', Carbon::now()->subDays(90));
            //     } elseif ($period == '1yar') {
            //         $query->where('uap_history.created_at', '>=', Carbon::now()->subDays(365));
            //     } else {
            //         $query->where('balance_usd', '>', 0);
            //     }
            // })
            // ->where('uap_history.user_id', $userId)
            ->orderBy('uap_history.created_at', 'ASC')
            ->get();

        return $dataList;
    }

    public function getRatePoolHistory(int $ratePoolId, int $userId, ?string $transactionType = null)
    {
        $dataList = $this->where('user_id', $userId)
            ->where('rate_pool_id', $ratePoolId)
            ->when($transactionType, fn($query) => $query->where('transaction_type', $transactionType))
                         ->orderBy('id', 'ASC')
        ->get();

        return $dataList;
    }

    public function prepareForPrint($historyList)
    {
        $locale = app()->getLocale();
        $ret = $historyList->map(function ($item) use ($locale) {
            $item = clone $item;
            $item->date_print = __(Carbon::parse($item->created_at)->format('F')) . Carbon::parse($item->created_at)->format(' j, Y');
            $item->time_print = Carbon::parse($item->created_at)->format($locale == 'en' ? 'g:i A' : 'H:i');

            $item->transaction_type_print = self::$transactionTypes[$item->transaction_type]['name_tr'];

            //$prevBalance = isset($historyList[$i+1]) ? $historyList[$i+1]->balance_usd : 0;

            //$item->balance_change = $item->balance_usd - $prevBalance;

            //var_dump(round($item->balance_usd, 2));
            $item->balance_usd_print = number_format($item->balance_usd, 2);
            $item->deposit_usd_print = number_format($item->deposit_usd, 2);
            //$item->balance_change_print = sprintf("%+g", number_format($item->balance_change, 2));
            //$item->balance_change_print = number_format($item->balance_change, 2);
            //$item->balance_change_print = ($item->balance_change > 0 ? '+' : '') . $item->balance_change_print;

            return $item;
        });

        //dd($historyList->all());
        return $ret;
    }

    public function fetchCommissionsList($historyList)
    {
        $historyList = $historyList->filter(function ($item) {
            return $item->commission_usd > 0;
        })->map(function ($item) {
            $item = clone $item;

            $item->transaction_type = 'M';
            $item->deposit_usd = -1 * intval($item->commission_usd);
            $item->created_at = Carbon::parse($item->created_at)->subSecond()->toDateTimeString();

            return $item;
        });

        return $historyList;
    }
}
