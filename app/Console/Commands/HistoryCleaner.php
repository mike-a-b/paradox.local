<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AssetHistory;
use App\Models\AssetPoolHistory;
use App\Models\UserBalanceHistory;
use Carbon\Carbon;

class HistoryCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leave only 1h data in history';

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
        $this->assets();

        $this->pools();

        $this->userProfiles();        
        
        return 0;
    }    

    protected function assets() {
        $chunkLen = 20;        
        AssetHistory::where('created_at', '>', Carbon::now()->subHours(24))->where('created_at', '<', Carbon::now()->subHours(1))
            ->chunkById($chunkLen, function ($dataList) {                
                foreach ($dataList as $item) {
                    $minutes = Carbon::parse($item->created_at)->format('i');
                    if (intval($minutes) > 1) { // H:00, H:01 - ok
                        echo 'assets ', $item->created_at, "\n";
                        $item->delete();
                    }                    
                }                                                        
            });
    }

    protected function pools() {
        $chunkLen = 20;        
        AssetPoolHistory::where('created_at', '>', Carbon::now()->subHours(24))->where('created_at', '<', Carbon::now()->subHours(1))
            ->chunkById($chunkLen, function ($dataList) {                
                foreach ($dataList as $item) {
                    $minutes = Carbon::parse($item->created_at)->format('i');
                    if (intval($minutes) > 1) { // H:00, H:01 - ok
                        echo 'pools ', $item->created_at, "\n";
                        $item->delete();
                    }                    
                }                                                        
            });
    }

    protected function userProfiles() {
        $chunkLen = 20;        
        UserBalanceHistory::where('created_at', '>', Carbon::now()->subHours(24))->where('created_at', '<', Carbon::now()->subHours(1))
            ->chunkById($chunkLen, function ($dataList) {                
                foreach ($dataList as $item) {
                    $minutes = Carbon::parse($item->created_at)->format('i');
                    if (intval($minutes) > 1) { // H:00, H:01 - ok
                        echo 'userBalances ', $item->created_at, "\n";
                        $item->delete();
                    }                    
                }                                                        
            });
    }
}