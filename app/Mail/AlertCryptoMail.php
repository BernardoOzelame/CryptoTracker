<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertCryptoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $coinName;
    public $alertValue;

    public function __construct($user, $coinName, $alertValue)
    {
        $this->user = $user;
        $this->coinName = $coinName;
        $this->alertValue = $alertValue;
    }

    public function build()
    {
        return $this->view('emails.alert_crypto')
            ->subject('Alerta de Criptomoeda')
            ->with([
                'userName' => $this->user->name,
                'coinName' => $this->coinName,
                'alertValue' => $this->alertValue,
            ]);
    }
}

