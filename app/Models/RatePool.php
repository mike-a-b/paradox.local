<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AssetPoolItem;
use App\Models\AssetPoolGroup;
use App\Models\Traits\HasLogs;

class RatePool extends Model
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
        'currency_id',
        'logo',
        'rate',
        'asset_pool_group_id',
        'is_active',
        'pos',
    ];

    public function group()
    {
        return $this->hasOne(AssetPoolGroup::class, 'id', 'asset_pool_group_id');
    }
    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function getAll($id=0, $isActive=-1) {
        $dataList = self::with('group')
                        ->when(!empty($id), function($query) use ($id) {
                            $query->where('id', $id);
                        })
                        ->when($isActive!=-1, function($query) use ($isActive) {
                            $query->where('is_active', $isActive);
                        })
                        ->get();

        $dataList = $dataList->sortBy([
                        ['group.pos', 'asc'],
                        ['rate', 'desc'],
                    ]);

        return $dataList;
    }

    public function commit($fields, $mode='create') {
        if ($mode == 'create') {
            $pos = $this->max('pos') ?? 0;
            $pos++;
        } else {
            $pos = $fields['pos'];
            $where = ['id' => $fields['id']];
        }

        $data = [
            'name' => $fields['name'],
            'name_short' => $fields['name_short'],
            'rate' => $fields['rate'],
            'currency_id' => $fields['currency_id'],
            'description' => $fields['description'],
            'description_ru' => $fields['description_ru'],
            'asset_pool_group_id' => $fields['asset_pool_group_id'],
            'pos' => $pos,
            'is_active' => empty($fields['is_active']) ? 0 : 1
        ];
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
}
