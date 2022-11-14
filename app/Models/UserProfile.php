<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserAssetPool;

class UserProfile extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'balance_usd',
        'balance_usdt',
        'deposits_usd',
        'withdraws_usd',
        'deposits_usdt',
        'withdraws_usdt',
        'second_name',
        'phone',
        'telegram',
        'ava',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateBalance($userId=0, $params=[]) {
        $balance = UserAssetPool::where('user_id', $userId ? $userId : $this->user_id)->sum('price_usd');
        if ($userId) {
            $item = $this->where('user_id', $userId)->first();
            //$this->where('user_id', $userId)->update($data);
        } else {
            $item = $this;
            //$this->balance_usd = $balance;
        }

        $data = ['balance_usd' => $balance];
        if (isset($params['deposit_change'])) {
            $depositChange = floatval($params['deposit_change']);
            if ($depositChange > 0) {
                $data['deposits_usd'] = $item->deposits_usd + $depositChange;
                $data['withdraws_usd'] = $item->withdraws_usd;
            } else {
                $data['withdraws_usd'] = $item->withdraws_usd + abs($depositChange);
                $data['deposits_usd'] = $item->deposits_usd;
            }
            //dd($depositChange);
            $data['balance_usdt'] = $item->balance_usdt - $depositChange;
            $item->fill($data)->save();
        }
        //$this->where('user_id', $item->user_id)->update($data);
        //$this->save();

        return $item;
    }

    public function updateBalanceUsdt($userId=0, $params=[]) {
        if ($userId) {
            $item = $this->where('user_id', $userId)->first();
        } else {
            $item = $this;
        }
        $balance = $item->balance_usdt;

        $data = ['balance_usdt' => $balance];
        if (isset($params['deposit_change'])) {
            $depositChange = floatval($params['deposit_change']);
            if ($depositChange > 0) {
                $data['deposits_usdt'] = $item->deposits_usdt + $depositChange;
            } else {
                $data['withdraws_usdt'] = $item->withdraws_usdt + abs($depositChange);
            }
            $data['balance_usdt'] = $balance + $depositChange;

            $item->fill($data)->save();
            //$item = $this->where('user_id', $userId)->first();
        }

        return $item;
    }
}
