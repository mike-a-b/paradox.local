<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetPoolItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {        
        if (isset($this->deep)) {             
            unset($this->asset->created_at);
            unset($this->asset->updated_at);
        }

        return [
            'id' => $this->id,
            'asset_pool_id' => $this->asset_pool_id,
            'asset_amount' => $this->asset_amount,
            'asset_id' => $this->asset_id,
            'fraction' => $this->fraction,
            'price_usd' => $this->price_usd,            
            'pos' => $this->pos,            
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'asset' => $this->when(isset($this->deep), $this->asset)
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
