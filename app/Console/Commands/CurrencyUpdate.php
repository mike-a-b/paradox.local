<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Currency;
use App\Services\CurrencyRatesApi;

class CurrencyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency rates to usd';

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
        $currencyRatesApi = new CurrencyRatesApi();
        $rates = $currencyRatesApi->getUsdRatesList();
        $rates = (array)$rates;

        $currencyList = Currency::all();
        foreach ($currencyList as $curr) {            
            if (isset($rates["USD{$curr->symbol}"])) {
                $curr->price_usd = $rates["USD{$curr->symbol}"];
                $curr->save();
            }
        }
        //dd($rates);
        
        return 0;
    }
}