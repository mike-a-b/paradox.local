<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\ExchangeBotLog;

class ExchangeBotLogResource extends JsonResource
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
            'exchange_id' => $this->exchange_id,
            'operation_type' => ExchangeBotLog::$oparationTypeData[$this->operation_type_id]['name_short'],
            'user_id' => $this->user_id,                                    
            'value_crypto' => $this->value_crypto,
            'value_fiat' => $this->value_fiat,
            'asset_pool_id' => $this->asset_pool_id,
            'asset_pool_name' => $this->asset_pool_name,
            'asset_pool_name_short' => $this->asset_pool_name_short,
            'creator_user_id' => $this->creator_user_id,
            'user_name' => $this->user_name,            
            'creator_user_name' => $this->creator_user_name,
            'api_request' => $this->api_request,
            'created_at' => Carbon::createFromTimestamp($this->request_ts)->toDateTimeString(),         
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
