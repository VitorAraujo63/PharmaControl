<?php

namespace App\Livewire;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use PragmaRX\Google2FA\Google2FA;

#[Layout('layouts.app')]
class TwoFactorManage extends Component
{
    public $secret;

    public $qrCodeUrl;

    public $qrCodeSvg;

    public $code;

    public $showQrCode = false;

    public function enableTwoFactor()
    {
        $google2fa = new Google2FA;

        $this->secret = $google2fa->generateSecretKey();

        $this->qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            Auth::user()->email,
            $this->secret
        );
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd
        );

        $writer = new Writer($renderer);

        $this->qrCodeSvg = $writer->writeString($this->qrCodeUrl);

        $this->showQrCode = true;
    }

    public function confirmTwoFactor()
    {
        $google2fa = new Google2FA;

        $valid = $google2fa->verifyKey($this->secret, $this->code);

        if ($valid) {
            $user = Auth::user();
            $user->google2fa_secret = $this->secret;
            $user->save();

            session()->put('2fa_verified', true);

            $this->showQrCode = false;
            session()->flash('success', '2FA Ativado com sucesso!');
        } else {
            $this->addError('code', 'Código inválido. Tente novamente.');
        }
    }

    public function disableTwoFactor()
    {
        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->save();

        session()->forget('2fa_verified');
        session()->flash('success', '2FA Desativado.');
    }

    public function render()
    {
        return view('livewire.two-factor-manage');
    }
}
