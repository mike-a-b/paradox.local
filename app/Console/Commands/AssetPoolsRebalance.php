<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AssetPool;
use App\Services\CoinmarketcapCategory;
use Carbon\Carbon;
use Exception;

class AssetPoolsRebalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset-pools:rebalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load cryptocurrency-category list from CoinMarketCap Api and rebalance all pools';

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
    public function handle(AssetPool $assetPool)
    {
        //$ASSET_POOL_GROUP_ID = 17;
        $coinmarketcapCategory = new CoinmarketcapCategory();
    
        $assetPools = AssetPool::where('asset_pool_template_id', '>', 0)->get();

        // dd($list->toArray());

        foreach ($assetPools as $assetPool) {
            $rebalancedNextAt = $assetPool->getRebalanceDateNext($assetPool->rebalanced_at, $assetPool->rebalance_frequency);            
            if (Carbon::parse($rebalancedNextAt)->timestamp <= Carbon::now()->timestamp || empty($assetPool->rebalanced_at)) {
                $sourceType = empty($assetPool->is_topmarketcap_based) ? 'cmk_category' : 'top_cmk_category';
                echo $assetPool->name, ' ', $sourceType, ' ', ($assetPool->rebalanced_at ?? 'NULL'), ' ', $rebalancedNextAt, "\n";                        
                //echo Carbon::parse('2022-04-01 24:00:00')->timestamp, " <= ", Carbon::parse('2022-04-02 00:00:00')->timestamp, "\n"; exit;
                $coinmarketcapCategory->rebuildAssetPool($assetPool, $sourceType);
                $coinmarketcapCategory->rebuildUserAssetPools($assetPool->id);            

                sleep(1);
            }
        }

        return 0;
    }
}
