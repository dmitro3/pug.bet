<?php

namespace App\Http\Livewire;

use Livewire\Component;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
 use Carbon\Carbon;

class Faucet extends Component
{
    use Actions;

    public function notificateSuccess($user, $amount)
    {
        $this->notification()->success($title = 'Success!', $description = 'Added '.$amount.'$ faucet to your balance.');
        event(new \App\Events\PlaySound($user, "/sounds/coins.mp3"));
    }

    public function useFaucet() { 
        $user = auth()->user();
        if(!$user) return $this->notification()->error($title = 'Not able to redeem faucet', $description = 'You are not logged in.');
        $getUserBonusHistory = \App\Models\BonusHistory::where('u', $user->_id)->first();
        $faucet_basevalue = \App\Models\Settings::where('key', 'faucet_basevalue')->first()->value;
        $faucet_maxbalance = \App\Models\Settings::where('key', 'faucet_maxbalance')->first()->value;
        $faucet_cooldown = \App\Models\Settings::where('key', 'faucet_cooldown')->first()->value;

        $current_timestamp = Carbon::now()->timestamp;
        $min_timestamp = Carbon::now()->subMinute($faucet_cooldown)->timestamp;

        if(!$getUserBonusHistory) {
           \App\Models\BonusHistory::create([
                'u' => $user->_id,
                'freespins_initiated' => 0,
                'rakeback_lastused' => 0,
                'rakeback_total' => 0,
                'faucet_total' => 0,
                'faucet_lastused' => 0,
                'promocode_total' => 0,
                'promocode_freespins' => 0,
                'promocode_usedtoday' => 0,
                'promocode_lastused' => 0
            ]);
            
            $getUserBonusHistory = \App\Models\BonusHistory::where('u', $user->_id)->first();
        }
        $userViplevel = auth()->user()->viplevel;
        if($user->balance() > $faucet_maxbalance) return $this->notification()->error($title = 'Not able to redeem faucet', $description = 'Your balance is too large to use faucet.');
        if($getUserBonusHistory->faucet_lastused > $min_timestamp) return $this->notification()->error($title = 'Not able to redeem faucet', $description = 'You have used faucet recently, please wait.');

        $getcurrentVipInfo = \App\Models\VIP\VipLevels::where('level', '=', ($user->viplevel))->first();
        $faucetVipBonus = ($faucet_basevalue / 100) * $getcurrentVipInfo->faucet_bonus;
        $endNettoFaucet = number_format(($faucet_basevalue + $faucetVipBonus), 2, ".", "");
        $getUserBonusHistory->update([
                'faucet_total' => ($getUserBonusHistory->faucet_total ?? 0) + $endNettoFaucet,
                'faucet_lastused' => $current_timestamp
        ]);

        $user->add(
            $endNettoFaucet,
            "usd",
            "faucet",
            json_encode(["faucet" => $endNettoFaucet, "faucet_total" => $getUserBonusHistory->faucet_total])
        );
                return self::notificateSuccess($user, $endNettoFaucet);
    }

    public function render()
    {
        return view('livewire.faucet');
    }
}
