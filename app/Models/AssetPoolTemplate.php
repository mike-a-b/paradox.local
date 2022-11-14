<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetPoolTemplate extends Model
{
    use HasFactory, HasLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'asset_count',
        'body',
        'is_active',
        'pos',
    ];

    public function createBody($assetCount) {
        $body = [];
        for ($i=0; $i < $assetCount; $i++) {
            $body[] = [
                'index' => $i,
                'fraction' => 0.0
            ];
        }

        return $body;
    }

    public function commit($fields, $mode='create') {

        $assetCount = $fields['asset_count'];

        $data = [
            'name' => $fields['name'],
            'asset_count' => $assetCount,
            'body' => $fields['body'] ?? json_encode($this->createBody($assetCount)),
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
}
