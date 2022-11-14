<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetPoolTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->asset);
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'asset_count' => $this->asset_count,
            'is_active' => $this->is_active,
            'body' => json_decode($this->body)
            //'created_at' => $this->created_at->toDateTimeString(),            
            //'created_at' => $this->created_at->format('Y-m-d')
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
