<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use PragmaRX\Google2FA\Google2FA;

#[Layout('layouts.app')]
class TwoFactorChallenge extends Component
{
    public $code;

    public function verify()
    {
        $user = Auth::user();
        $google2fa = new Google2FA;

        if ($google2fa->verifyKey($user->google2fa_secret, $this->code)) {
            session()->put('2fa_verified', true);

            return redirect()->intended(route('dashboard'));
        }

        $this->addError('code', 'Código incorreto.');
    }

    public function render()
    {
        return view('livewire.two-factor-challenge');
    }
}
