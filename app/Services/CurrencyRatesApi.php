<?php

namespace App\Services;

class CurrencyRatesApi
{
    public $apiKey = '';
    
    public $baseUrl = 'http://api.currencylayer.com';

    public function __construct($apiKey='') {        
        $this->apiKey = empty($apiKey) ? config('app.currencylayer_api_key') : '';        
    }

    public function makeRequest($url, $parameters=[]) {                        
        $baseUrl = $this->baseUrl . $url;

        $headers = [
            'Accepts: application/json'            
        ];
        $qs = http_build_query($parameters);
        $request = $baseUrl . (empty($qs) ? '' : "?$qs") . "?access_key={$this->apiKey}"; 

        //dd($request);
        $handle = curl_init($request);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($handle);        

        if ($response === false) {
            $errno = curl_errno($handle);
            $error = curl_error($handle);
            error_log("Curl returned error $errno: $error\n");
            curl_close($handle);
            return false;
        }
        curl_close($handle);

        //var_dump($response);

        $ret = json_decode($response);  
        
        if (isset($ret->success) && $ret->success) {
            return $ret->quotes;
        }              

        return false;
    }    

    public function getUsdRatesList() {
        
        $ret = $this->makeRequest('/live');

        return $ret;
    }    
}
