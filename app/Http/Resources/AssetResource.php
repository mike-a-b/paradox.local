<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
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
            'name' => $this->name,
            'symbol' => $this->symbol,
            'slug' => $this->slug,
            'price_usd' => $this->price_usd,            
            'logo' => $this->logo,            
            'pos' => $this->pos,
            'asset_type_id' => $this->asset_type_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),            
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
