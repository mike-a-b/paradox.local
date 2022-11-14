<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_id' => $this->type_id,
            'type_name' => $this->type_name,
            'code' => $this->code,
            'title' => $this->title,                        
            'description' => $this->description,                        
            'is_checked' => $this->is_checked,            
            'created_at' => $this->created_at->toDateTimeString(),         
        ];
        //return parent::toArray($request);
    }
    /**
     * Получить дополнительные данные, возвращаемые с массивом ресурса.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function with($request)
    // {
    //     return [
    //         'meta' => [
    //             'key' => 'value',
    //         ],
    //     ];
    // }
}
