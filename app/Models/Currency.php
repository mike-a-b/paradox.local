<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'symbol',
        'symbol_short',
        'price_usd',
        'is_active',
        'pos',
    ];

    // protected $casts = [
    //     'updated_at' => 'datetime:Y-m-d H:i:s',
    //     'created_at' => 'datetime:Y-m-d H:i:s',
    // ];

    public function getRateUsd($fromSymbol) {
        $ret = $this->where('symbol', $fromSymbol)->first();

        return $ret->price_usd;
    }

    public function commit($fields, $mode='create') {
        if ($mode == 'create') {
            $pos = $this->max('pos') ?? 0;
            $pos++;
        } else {
            $pos = $fields['pos'];
        }
        return self::updateOrCreate(
            [
                'name' => $fields['name'],
                'symbol' => $fields['symbol']
            ],
            [
                'price_usd' => $fields['price_usd'] ?? 0,
                'symbol_short' => $fields['symbol_short'],
                'pos' => $pos
            ]
        );
    }
}
