<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeInfo extends Model
{
    use HasFactory, HasLogs;

    protected $table = 'exchange_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'balance_usd',
        'users_balance_usd',
        'commissions_usd',
        'deposits_usd',
        'withdraws_usd',
        // 'meta',
    ];

    // public function getAll($params=[], $limits=[0, 100]) {
    //     $ret = self::query()
    //                 ->when(!empty($params['query']), function($query) use ($params) {
    //                     $query->where('name', 'like', "{$params['query']}%");
    //                 })
    //                 ->orderBy('pos', 'ASC')
    //                 ->offset($limits[0])->limit($limits[1])
    //                 ->get();
    //     return $ret;
    // }

    public function commit($fields) {
        $self = $this->findOrFail(1);
        if (isset($fields['commission_usd'])) {
            $self->commissions_usd += $fields['commission_usd'];
            $self->balance_usd += $fields['commission_usd'];
        }
        if (isset($fields['user_balance_usd'])) {
            $self->users_balance_usd += $fields['user_balance_usd'];
        }
        if (isset($fields['deposit_usd'])) {
            $self->deposits_usd += $fields['deposit_usd'];
            $self->users_balance_usd += $fields['deposit_usd'];
        }
        if (isset($fields['withdraw_usd'])) {
            $self->withdraws_usd += $fields['withdraw_usd'];
            $self->users_balance_usd -= $fields['withdraw_usd'];
        }
        $self->save();
    }
}
