<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cryptocurrency:update')->everyTenMinutes();
		//$schedule->command('cryptocurrency:update')->everyThirtyMinutes();
       // $schedule->command('rate-pool:update')->everyMinute();
        //$schedule->command('currency:update')->dailyAt('00:00');
        $schedule->command('asset-pools:rebalance')->dailyAt('00:00');
        $schedule->command('rate-pool:update')->dailyAt('00:00');
        //$schedule->command('cryptocurrency:update')->everyFifteenMinutes(); //TenMinutes();
        //$schedule->command('history:clear')->hourly();
		
		

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
