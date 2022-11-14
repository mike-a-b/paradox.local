<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestPreparation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tests:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare test enviromantce before tests start';

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
        $backupFile = storage_path('tests/db.sql');

        define('DB_NAME', env('DB_DATABASE'));        
        define('DB_NAME_TEST', env('TESTS_DB_NAME'));
        define('DB_USER', env('DB_USERNAME'));
        define('DB_PASSWORD', env('DB_PASSWORD'));        
        define('MYSQLDUMP', str_replace(['[DB_USER]', '[DB_PASSWORD]'], [DB_USER, DB_PASSWORD], env('MYSQLDUMP_PATH')));  
        define('MYSQL', str_replace(['[DB_USER]', '[DB_PASSWORD]'], [DB_USER, DB_PASSWORD], env('MYSQL_PATH')));  
                
        $com = MYSQLDUMP." ".DB_NAME." > \"".$backupFile."\"";
        // echo $com; exit;
        shell_exec($com);

        $com = MYSQL." ".DB_NAME_TEST." < \"".$backupFile."\"";
        //echo $com; exit;
        shell_exec($com);

        //dd($backupFile);

        return 0;
    }
}
