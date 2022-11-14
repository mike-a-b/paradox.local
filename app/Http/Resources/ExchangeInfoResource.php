<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeInfoResource extends JsonResource
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
            'balance_usd' => $this->balance_usd,
            'users_balance_usd' => $this->users_balance_usd,
            'commissions_usd' => $this->commissions_usd,
            'deposits_usd' => $this->deposits_usd,                        
            'withdraws_usd' => $this->withdraws_usd,       
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
