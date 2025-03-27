<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Restablecer tu contraseña en SCDAA')
            ->greeting("¡Hola!")
            ->line("Recibimos una solicitud para restablecer tu contraseña en la app SCDAA.")
            ->line("Si tú hiciste esta solicitud, usá el siguiente token en la app:")
            ->line("**{$this->token}**")
            ->line("Este token es válido por 60 minutos.")
            ->line("Si no solicitaste esto, simplemente ignorá este mensaje.")
            ->salutation("— El equipo de SCDAA");            
    }
}
