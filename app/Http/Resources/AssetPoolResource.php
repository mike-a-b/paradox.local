<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
//use App\Models\AssetPool;


class AssetPoolResource extends JsonResource
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
            'asset_pool_template_id' => $this->asset_pool_template_id,            
            'price_usd' => $this->price_usd,            
            'pos' => $this->pos,
            'meta' => $this->meta,
            'rebalance_frequency' => $this->rebalance_frequency,
            'rebalanced_at' => $this->rebalanced_at,
            'rebalanced_next_at' => $this->getRebalanceDateNext($this->rebalanced_at, $this->rebalance_frequency),
            'is_active' => $this->is_active,
            'is_topmarketcap_based' => $this->is_topmarketcap_based,
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
