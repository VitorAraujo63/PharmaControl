<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class TwoFactorChallenge extends Component
{
    public $code;

    public function verify()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

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
