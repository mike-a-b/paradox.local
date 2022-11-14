<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Mnemonic;
use App\Models\Address;
use App\Models\CoinMnemo;
use App\Services\Etherjs;

class MnemoToAddress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mnemonic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mnemonic $mnemonic)
    {
        $this->mnemonic = $mnemonic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mnemonic = $this->mnemonic;
        $coinMnemos = CoinMnemo::where('coin_id', $mnemonic->coin_id)->get();
        foreach ($coinMnemos as $cMnemo) {
            for ($i = 0; $i < $cMnemo->addr_count; $i++) {
                $path = CoinMnemo::pathNum($cMnemo->path, $i);
                $address = Etherjs::mnemoicsToAddress($mnemonic->mnemo, $path);
                // echo $path, '<br>';
                // print_r($address);
                // echo '<br>';
                $row = Address::firstOrCreate([
                    'address' => $address,
                    'user_id' => $mnemonic->user_id,
                    'coin_id' => $mnemonic->coin_id,
                    'mnemonic_id' => $mnemonic->id,
                    'mnemo_path' => $path,
                    'total_balance' => 0,
                    'nft_net_worth' => 0,
                    'tg_notice' => 0,                    
                ]);
                $row->updated_at = null;
                $row->save();                
            }
        }

        // $addressList = Etherjs::mnemoicsToAddress($this->mnemonic->mnemo);

        // $this->address->address =
        //     $this->address->updated_at = null; // \Carbon\Carbon::now()->subDays(1);
        // //$this->timestamps = false;
        // $this->address->save();
    }
}