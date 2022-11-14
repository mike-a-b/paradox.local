<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'name',
        'value',
        'pos'
    ];

    const TYPE_ID_ASSET_POOL_BUY = 1;
    const TYPE_ID_ASSET_POOL_SELL = 2;

    public function getAll($params=[], $limits=[0, 100]) {
        $ret = self::query()
                    ->when(!empty($params['query']), function($query) use ($params) {
                        $query->where('name', 'like', "{$params['query']}%");
                    })
                    ->orderBy('pos', 'ASC')
                    ->offset($limits[0])->limit($limits[1])
                    ->get();
        return $ret;
    }

    public static function calculateCommission(int $typeId, float $value, int $precision=2) : float {
        $BC_BASE = config('app.bc_base');
        $value = abs($value);
        $row = self::where('type_id', $typeId)->firstOrFail();
        $commPerc = $row['value'];
        // $ret = $value*$commPerc/100;
        $ret = bcdiv(bcmul($value, $commPerc, $BC_BASE), 100, $BC_BASE);
        $ret = round($ret, $precision);

        return $ret;
    }

    // public function insert($fields) {
    //     $pos = $this->max('pos') ?? 0;
    //     return self::updateOrCreate(
    //         [
    //             'type_id' => $fields['type_id'],
    //         ],
    //         [
    //             'name' => $fields['name'],
    //             'value' => $fields['value'],
    //             'pos' => $pos + 1
    //         ]
    //     );
    // }
}
