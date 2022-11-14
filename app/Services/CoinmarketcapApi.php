<?php

namespace App\Services;

class CoinmarketcapApi
{
    public $apiKey = '';
    
    public $baseUrl = 'https://pro-api.coinmarketcap.com/v1';

    protected $logService = null;

    public function __construct($apiKey='') {        
        $this->apiKey = empty($apiKey) ? config('app.coinmarketcap_api_key') : '';        
    }

    public function setLogService($logService) {
        $this->logService = $logService;
    }

    public function makeRequest($url, $parameters=[]) {                        
        $baseUrl = $this->baseUrl . $url;

        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: '.$this->apiKey
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = $baseUrl . (empty($qs) ? '' : "?$qs"); // create the request URL

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
            return null;
        }
        curl_close($handle);

        $ret = json_decode($response);  
        
        if (isset($ret->status->error_code) && $ret->status->error_code == 0) {
            return $ret->data;
        } else {            
            if ($this->logService) {
                //dd($ret);
                $this->logService->log([
                    'type_id' => NotificationsLogService::TYPE_ID_API_ERROR,
                    'code' => $ret->status->error_code ?? -1,
                    'title' => 'Coinmarketcap API',
                    'description' => ($ret->status->error_message ?? ' ') . ($ret->status->notice ?? '')                    
                ]);
            }
        }

        return null;
    }    

    public function getCryptoCurrencyList($start=1, $count=100) {
        $parameters = [
            'start' => $start,
            'limit' => $count,
            'convert' => 'USD'
        ];
        $ret = $this->makeRequest('/cryptocurrency/listings/latest', $parameters);

        return $ret;
    }

    public function getCryptoCurrencyInfo($parameters) {        
        $ret = $this->makeRequest('/cryptocurrency/info', $parameters);

        return $ret;
    }

    public function getCryptoCurrencyQuotesLatest($parameters) {        
        $ret = $this->makeRequest('/cryptocurrency/quotes/latest', $parameters);

        return $ret;
    }

    public function getCategoryList($start=1, $count=100) {
        $parameters = [
            'start' => $start,
            'limit' => $count,            
        ];
        $ret = $this->makeRequest('/cryptocurrency/categories', $parameters);

        return $ret;
    }
    
    public function getCategory($categoryId, $start=1, $count=100) {
        $parameters = [
            'id' => $categoryId,
            'start' => $start,
            'limit' => $count,            
        ];
        $ret = $this->makeRequest('/cryptocurrency/category', $parameters);

        return $ret;
    }
}
