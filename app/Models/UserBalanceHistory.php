<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Asset;
use App\Models\Traits\HasLogs;
use Carbon\Carbon;

class UserBalanceHistory extends Model
{
    use HasFactory, HasLogs;

    protected $table = 'user_balance_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'balance_usd',
        'deposits_usd',
        'withdraws_usd',
        'balance_usdt',
        'deposits_usdt',
        'withdraws_usdt',
        'rate_ustd_to_usd',
        'usdt_for_transaction',
        'usd_for_transaction'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commit($fields)
    {
        $data = [
            'user_id' => $fields['user_id']
        ];

        if (isset($fields['balance_usd'])) {
            $data['balance_usd'] = $fields['balance_usd'];
        }
        if (isset($fields['balance_usdt'])) {
            $data['balance_usdt'] = $fields['balance_usdt'];
        }
        if (isset($fields['deposits_usd'])) {
            $data['deposits_usd'] = $fields['deposits_usd'];
        }
        if (isset($fields['withdraws_usd'])) {
            $data['withdraws_usd'] = $fields['withdraws_usd'];
        }
        if (isset($fields['deposits_usdt'])) {
            $data['deposits_usdt'] = $fields['deposits_usdt'];
        }
        if (isset($fields['withdraws_usdt'])) {
            $data['withdraws_usdt'] = $fields['withdraws_usdt'];
        }
        if (isset($fields['rate_ustd_to_usd'])) {
            $data['rate_ustd_to_usd'] = $fields['rate_ustd_to_usd'];
        } else {
            $data['rate_ustd_to_usd'] = floatval(Asset::find(Asset::USDT_ID)->price_usd);
        }
        if (isset($fields['deposit_change'])) {
            $data['usdt_for_transaction'] = $fields['deposit_change'];
        }

        return self::create($data);
    }

    public function getRowAt($userId, $date)
    {
        $ret = $this->where('user_id', $userId)->where('created_at', '<=', $date)
            ->orderBy('created_at', 'DESC')->first();

        return $ret;
    }

    public function getCompressedHistory(int $userId, array $keyFields,$period)
    {
        $list = $this->where('user_id', $userId)
            ->where(function ($query) use ($period) {
                    $query->where('balance_usd', '>', 0);
            })
            ->orderBy('created_at', 'ASC')
            ->get();

        $prevKey = '';
        $prevItem = null;
        $list = $list->filter(function ($item) use ($keyFields, &$prevKey, &$prevItem) {
            $key = '';
            foreach ($keyFields as $fKey) {
                $key .= $item->{$fKey} . '-';
            }
            if ($prevKey == $key) {
                return false;
            } else {
                if ($prevItem) {
                    foreach ($keyFields as $fKey) {
                        if ($item->{$fKey} != $prevItem->{$fKey}) {
                            $item->changer_field = $fKey;
                            break;
                        }
                    }
                }
                $ret = true;
                $isZerroble = true;
                foreach ($keyFields as $fKey) {
                    if (floatval($item->{$fKey}) != 0) {
                        $isZerroble = false;
                        break;
                    }
                }
                if ($isZerroble) {
                    $ret = false;
                }
                $prevItem = $item;
                $prevKey = $key;
                return $ret;
            }
        });

        $ret = $list->reverse();

        return $ret;
    }

    public function prepareForPrint($historyList)
    {
        $locale = app()->getLocale();
        $historyList = $historyList->map(function ($item) use ($locale) {
            $item->date_print = __(Carbon::parse($item->created_at)->format('F')) . Carbon::parse($item->created_at)->format(' j, Y');
            $item->time_print = Carbon::parse($item->created_at)->format($locale == 'en' ? 'g:i A' : 'H:i');

            $item->transaction_type_print = $item->changer_field === 'deposits_usdt' ? 'Deposit USDT' : 'Withdraw USDT';

            $item->balance_usd_print = number_format($item->balance_usdt, 2);
            $item->deposit_usd_print = $item->changer_field === 'deposits_usdt' ? number_format($item->deposits_usdt, 2) : number_format($item->withdraws_usdt, 2);

            if (in_array($item->changer_field, ['deposits_usdt', 'withdraws_usdt'])) {
                $item->deposit_usd_print = number_format($item->usdt_for_transaction, 2);
            }

            return $item;
        });

        return $historyList;
    }
}
