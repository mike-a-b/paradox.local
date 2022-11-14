<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CoinmarketcapApi;
use App\Models\Asset;
use Exception;

class CryptocurrencyLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cryptocurrency:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load cryptocurrency list from CoinMarketCap Api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $assets = new Asset();
        $cmc = new CoinmarketcapApi();
        
        // $countPack = 100;
        // for ($iPack=0; $iPack < 100; $iPack++) {
        //     $list = $cmc->getCryptoCurrencyList(1 + $iPack*$countPack, $countPack);
        //     foreach ($list as $li) {
        //         //dd($li);
        //         echo $li->name, ' ', $li->symbol, ' ', $li->quote->USD->price, "\n";
        //         // try {
        //             $assets->insert([
        //                 'coinmarketcap_id' => $li->id,
        //                 'asset_type_id' => 1,
        //                 'name' => $li->name,
        //                 'symbol' => $li->symbol,        
        //                 'slug' => $li->slug,        
        //                 'price_usd' => $li->quote->USD->price ?? 0
        //             ]);            
        //         // } catch (Exception $e) {
        //         //     //print_r($e);
        //         //     //exit;
        //         // }
        //     }        
        //     sleep(1);    
        // }

        // exit;          
                
        $chunkLen = 100;
        Asset::where('id', '>', '0')
            ->chunkById($chunkLen, function ($asset) use ($cmc) {
                $ids = $asset->map(function ($li) {
                    return $li->coinmarketcap_id;
                })->join(',');          
                //$symbols = 'SIDUS';      
                $infoList = $cmc->getCryptoCurrencyInfo(['id' => $ids]);                   
                if (!empty($infoList)) {
                    foreach ($infoList as $coinInfo) {
                        //dd($coinInfo);
                        $item = Asset::where('coinmarketcap_id', $coinInfo->id)->first();
                        if ($item) {
                            $ff = dechex(rand(0, 255));
                            $logoPath = "assets/imgs/cryptocurrency/logo/$ff/";
                            if (!file_exists(public_path($logoPath))) {        
                                if(!mkdir(public_path($logoPath), 0777)) {
                                    echo 'cant create dir '.$logoPath.'/';
                                    exit;
                                }
                            }
                            echo $coinInfo->symbol, "\n";
                            $logoFile = "{$logoPath}{$coinInfo->symbol}-{$coinInfo->id}.png";
                            file_put_contents(public_path($logoFile), file_get_contents($coinInfo->logo));
                            $item->logo = $logoFile;
                            $item->save();                        
                        }
                    }
                }                
                sleep(1);
                // echo $symbols;             
                // dd($infoList);
            });

        //dd($list);   
        
        // $chunkLen = 20;
        // Asset::where('slug', 'TONCOIN')
        //     ->chunkById($chunkLen, function ($assets) use ($cmc) {
        //         // $symbols = $assets->map(function ($li) {
        //         //     return $li->symbol;
        //         // })->join(',');          
        //         //$symbols = 'SIDUS';    
        //         //dd($symbols);  
        //         foreach ($assets as $asset) {
        //             $infoList = $cmc->getCryptoCurrencyQuotesLatest(['symbol' => $asset->symbol]);                   
        //             //var_dump($infoList); //exit;
        //             if (!empty($infoList)) {
        //                 echo $asset->symbol, "\n";
        //                 foreach ($infoList as $coinInfo) {
        //                     //dd($coinInfo->slug);
        //                     $asset = Asset::where('symbol', $coinInfo->symbol)->first();                    
        //                     $asset->slug = $coinInfo->slug;
        //                     $asset->slug = $coinInfo->name;
        //                     $asset->save();                                                
        //                 }
        //             }                
        //             sleep(1);
        //         }
        //         // $infoList = $cmc->getCryptoCurrencyQuotesLatest(['symbol' => $symbols]);                   
        //         // //dd($infoList);
        //         // if (!empty($infoList)) {
        //         //     foreach ($infoList as $coinInfo) {
        //         //         dd($coinInfo);
        //         //         $asset = Asset::where('symbol', $coinInfo->symbol)->where('name', $coinInfo->name)->first();                    
        //         //         $asset->slug = $coinInfo->slug;
        //         //         $asset->save();                                                
        //         //     }
        //         // }                
        //         // sleep(1);
        //         // echo $symbols;             
        //         // dd($infoList);
        //     });

        // exit;      

        return 0;
    }
}
