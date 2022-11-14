<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserAssetPool;
use App\Services\UserRatePool;
use Carbon\Carbon;

class RatePoolUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rate-pool:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user rate pools balances at 00:00';

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
        $userRatePool = new UserRatePool();

        $chunkLen = 20;
        UserAssetPool::where('rate_pool_id', '>', 0) // is_active=1
            ->chunkById($chunkLen, function ($userAssetPools) use ($userRatePool) {                
                foreach ($userAssetPools as $userAssetPool) {  
                    $userRatePool->dailyProfit($userAssetPool);                    
                }                                                        
            });            
        
        return 0;
    }
}