<?php
namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class AlertCryptoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $coinName;
    public $alertValue;

    public function __construct(User $user, string $coinName, float $alertValue)
    {
        $this->user = $user;
        $this->coinName = $coinName;
        $this->alertValue = $alertValue;
    }

    public function handle()
    {
        $user = $this->user;
        $coinName = $this->coinName;
        $alertValue = $this->alertValue;

        // Enviar email usando o Mail
        Mail::to($user->email)->send(new \App\Mail\AlertCryptoMail($user, $coinName, $alertValue));
    }
}
