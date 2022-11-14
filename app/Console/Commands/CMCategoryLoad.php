<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CoinmarketcapApi;
use App\Models\AssetPoolTemplate;
use App\Models\AssetPool;
use App\Models\AssetPoolItem;
use App\Models\Asset;
use App\Services\DataRecalculator;
use Exception;

class CMCategoryLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cm-category:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load cryptocurrency-category list from CoinMarketCap Api';

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
        $ASSET_POOL_GROUP_ID = 17;

        $assets = new Asset();
        $cmc = new CoinmarketcapApi();
        $assetPool = new AssetPool();
        $assetPoolItem = new AssetPoolItem();
        $assetPoolTemplate = new AssetPoolTemplate();
        
        $assetPoolTpl = $assetPoolTemplate::query()->first();
        $assetPoolTplBody = json_decode($assetPoolTpl->body);        
        //$tplAssetCount = $assetPoolTpl->asset_count;

        // dd($assetPoolTpl->toArray());
        // dd($assetPoolTplBody);
                
        $count = 200;
        $list = $cmc->getCategoryList(1, $count);

        $list = collect($list)->sortByDesc('avg_price_change');
        //dd($list->toArray());

        
        //dd($coinsList);

        foreach ($list as $li) {
            //echo $li->name, ' ', $li->avg_price_change, "\n";

            $assetPoolOne = $assetPool->where('name', $li->name)->where('asset_pool_group_id', $ASSET_POOL_GROUP_ID)->first();

            if (empty($assetPoolOne)) {
                $data = [
                    'name' => $li->name,
                    'description' => $li->description,
                    'logo' => '',
                    'asset_type_id' => 1,
                    'is_active' => 1,
                    'asset_pool_group_id' => $ASSET_POOL_GROUP_ID,
                    'asset_pool_template_id' => $assetPoolTpl->id,
                    'meta' => json_encode($li),                    
                ];
        
                $assetPoolOne = $assetPool->commit($data);
            }            

            $tmp = $assetPoolItem->where('asset_pool_id', $assetPoolOne->id)->first();
            if ($tmp) {
                continue;
            }

            echo $li->name, ' ', $li->avg_price_change, "\n";

            $coinsList = $cmc->getCategory($li->id);

            if (!empty($coinsList->coins)) {                
                //$coinsArr = $coinsList->coins;
                
                $assetPoolItem->where('asset_pool_id', $assetPoolOne->id)->delete();

                foreach ($assetPoolTplBody as $tplAsset) {
                    $coin = array_shift($coinsList->coins);
                    if ($coin) {
                        $asset = $assets->where('symbol', $coin->symbol)->where('name', $coin->name)->first();                    
                        if ($asset) {
                            $assetPoolItem->commit([
                                'asset_pool_id' => $assetPoolOne->id,
                                'asset_id' => $asset->id,
                                'fraction' => $tplAsset->fraction,
                                //'price_usd' => $coin->quote->USD->price ?? 0,                                                         
                            ]);
                        }
                    }                    
                }  
                
                $assetPoolOne->commit($assetPoolOne->toArray(), 'update');
                $dataRecalculator = new DataRecalculator();
                $dataRecalculator->updatePoolsHistory($assetPoolOne->id);

                $assetPoolOne->is_active = 0;
                $assetPoolOne->save();
            }
            
            sleep(1);
        }

        return 0;
    }
}
