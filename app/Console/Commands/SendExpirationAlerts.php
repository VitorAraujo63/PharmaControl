<?php

namespace App\Console\Commands;

use App\Mail\LowStockAlertMail;
use App\Models\Batch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpirationAlerts extends Command
{
    protected $signature = 'alerts:send-expiration';

    protected $description = 'Verifica produtos vencendo e envia e-mail para admins';

    public function handle()
    {
        $today = Carbon::today();
        $date14 = $today->copy()->addDays(14);
        $date30 = $today->copy()->addDays(30);

        $expiring14 = Batch::with('product')
            ->where('quantity', '>', 0)
            ->whereDate('expiration_date', '>=', $today)
            ->whereDate('expiration_date', '<=', $date14)
            ->get();

        $expiring30 = Batch::with('product')
            ->where('quantity', '>', 0)
            ->whereDate('expiration_date', '>', $date14)
            ->whereDate('expiration_date', '<=', $date30)
            ->get();

        if ($expiring14->isEmpty() && $expiring30->isEmpty()) {
            $this->info('Nenhum produto próximo do vencimento.');

            return;
        }

        $recipients = User::whereIn('role', ['admin', 'manager'])
            ->where('status', 'ativo')
            ->pluck('email');

        if ($recipients->isEmpty()) {
            $this->error('Nenhum destinatário encontrado.');

            return;
        }

        foreach ($recipients as $email) {
            Mail::to($email)->send(new LowStockAlertMail($expiring14, $expiring30));
        }

        $this->info('E-mails de alerta enviados com sucesso!');
    }
}
