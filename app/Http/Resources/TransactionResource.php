<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Transaction;

class TransactionResource extends JsonResource
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
            'type_name_short' => Transaction::$typeData[$this->type_id]['name_short'],
            'from_user_id' => $this->from_user_id,                                    
            'value' => $this->value,
            'commission_value' => $this->commission_value,
            'pool_id' => $this->pool_id,
            'asset_pool_name' => $this->asset_pool_name,
            'asset_pool_name_short' => $this->asset_pool_name_short,
            'creator_user_id' => $this->creator_user_id,
            'from_user_name' => $this->from_user_name,
            'creator_user_name' => $this->creator_user_name,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),         
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
