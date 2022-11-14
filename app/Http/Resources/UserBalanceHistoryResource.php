<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBalanceHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this);

        $totalBalance = $this->balance_usd + $this->balance_usdt*$this->rate_ustd_to_usd;
        
        return [
            'total_balance_usd' => floor(100*$totalBalance)/100,
            // 'balance_usd' => floor(100*$this->balance_usd)/100,
            // 'deposits_usd' => floor(100*$this->deposits_usd)/100,
            // 'withdraws_usd' => floor(100*$this->withdraws_usd)/100,
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
