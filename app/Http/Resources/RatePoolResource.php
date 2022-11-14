<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatePoolResource extends JsonResource
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
            'name_short' => $this->name_short,
            'description' => $this->description,
            'description_ru' => $this->description_ru,
            'logo' => $this->logo,
            'asset_pool_group_id' => $this->asset_pool_group_id,
            'rate' => round($this->rate, 2),
            'currency_id' => $this->currency_id,
            'date_start' => $this->date_start,            
            'date_end' => $this->date_end,            
            'pos' => $this->pos,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),                        
            'group_name' => $this->group->name ?? '',
            'assets' => $this->when(isset($this->assets), $this->assets),
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
