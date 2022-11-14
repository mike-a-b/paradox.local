<?php

namespace App\Services;

use App\Models\NotificationsLog;

class NotificationsLogService
{    
    const TYPE_ID_API_ERROR = 1;

    const TYPE_ID_LABELS = [
        self::TYPE_ID_API_ERROR => 'API'
    ];

    public function log(array $data) {

        $notificationsLog = new NotificationsLog();
        
        $notificationsLog->commit([
            'type_id' => $data['type_id'],
            'code' => $data['code'] ?? 0,
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'is_checked' => $data['is_checked'] ?? 0,
        ]);
        
    }
}
