<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Etherjs
{
    public static function mnemoicsToAddress($mnemoics, $path)
    {
        // $cmd = 'node '.base_path().'/nodejs/mnemo_to_addr.js "'.$mnemoics.'"';
        // $ret = shell_exec($cmd);
        $process = new Process([config('app.nodejs'), base_path().'/nodejs/mnemo_to_addr.js', $mnemoics, $path]);
        $process->run();
        
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        $ret = $process->getOutput();

        //dd($ret);

        $ret = strpos($ret, '0x') !== false ? trim($ret) : '';

        return $ret;
    }    
}
