<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataRecalculator;

class CryptocurrencyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cryptocurrency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update cryptocurrency rates from CoinMarketCap Api and all dependencies';

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
        $dataRecalculator = new DataRecalculator();
        
        $dataRecalculator->updateAssets();

        $dataRecalculator->updatePoolsHistory();

        $dataRecalculator->updateUserPools();

        $dataRecalculator->updateUserProfiles();
        
        return 0;
    }
}