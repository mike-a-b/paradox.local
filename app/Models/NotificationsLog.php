<?php

namespace App\Models;

use App\Models\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationsLog extends Model
{
    use HasFactory, HasLogs;

    protected $table = 'notifications_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'code',
        'title',
        'description',
        'is_checked'
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
        return self::create(
            [
                'type_id' => $fields['type_id'],
                'code' => $fields['code'] ?? 0,
                'title' => $fields['title'] ?? '',
                'description' => $fields['description'] ?? '',
                'is_checked' => $fields['is_checked'] ?? 0,
            ]
        );
    }
}
