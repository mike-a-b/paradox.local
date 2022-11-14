<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\DB;
use App\Models\AssetPool;
use App\Models\Traits\HasLogs;

class AssetPoolGroup extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'g_type',
        'name',
        'description',
        'description_ru',
        'is_active',
        'pos',
    ];

    const G_TYPE_ASSET_POOL = 1;
    const G_TYPE_RATE_POOL = 2;

    const G_TYPE_TITLES = [
        self::G_TYPE_ASSET_POOL => 'Assets pool',
        self::G_TYPE_RATE_POOL => 'Rates pool',
    ];

    public function pools()
    {
        return $this->hasOne(AssetPool::class);
    }

    public function commit($fields, $mode='create') {

        $data = [
            'g_type' => $fields['g_type'],
            'name' => $fields['name'],
            'description' => $fields['description'],
            'description_ru' => $fields['description_ru'],
            'is_active' => empty($fields['is_active']) ? 0 : 1,
        ];

        if ($mode == 'create') {
            $pos = $this->max('pos') ?? 0;
            $data['pos'] = $pos + 1;
            return self::create($data);
        } else {
            $id = $fields['id'];
            return self::where('id', $id)->update($data);
        }
    }

    public function posMove(string $direction, int $id=null) {
        $row = $id ? $this->find($id) : $this;
        $pos = $row->pos;
        $gType = $row->g_type;
        $count = $this->count();

        $rowPrev = null;
        if ($direction == 'up' && $pos > 1) {
            $rowPrev = $this->where('pos', $pos - 1)->where('g_type', $gType)->first();
            $row->pos = $pos - 1;
        } elseif ($direction == 'down' && $pos < $count) {
            $rowPrev = $this->where('pos', $pos + 1)->where('g_type', $gType)->first();
            $row->pos = $pos + 1;
        }
        if ($rowPrev) {
            $row->save();
            $rowPrev->pos = $pos;
            $rowPrev->save();
        }
    }

    public function posRebase() {
        $listGrouped = self::query()
                    ->orderByDesc('g_type')
                    ->orderBy('pos')
                    ->get()
                    ->groupBy('g_type');

        foreach ($listGrouped as $list) {
            $pos = 1;
            foreach ($list as $li) {
                $li->pos = $pos++;
                $li->save();
            }
        }
    }
}
