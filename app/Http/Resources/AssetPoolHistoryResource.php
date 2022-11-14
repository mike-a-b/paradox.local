<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetPoolHistoryResource extends JsonResource
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
            //'id' => $this->id,
            //'asset_pool_id' => $this->asset_pool_id,
            'price_usd' => floor(100*$this->price_usd)/100,
            'created_at' => $this->created_at->toDateTimeString(),            
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
