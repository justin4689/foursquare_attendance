<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $notifiable->verificationUrl();

        return (new MailMessage)
            ->subject('Vérification de votre adresse email - ' . config('app.name'))
            ->greeting('Bienvenue ' . $notifiable->name . '!')
            ->line('Merci de vous être inscrit sur ' . config('app.name') . '.')
            ->line('Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email.')
            ->action('Vérifier mon adresse email', $verificationUrl)
            ->line('Si vous n\'avez pas créé de compte, aucune action n\'est requise.')
            ->salutation('Cordialement,<br>L\'équipe ' . config('app.name'))
            ->markdown('vendor.notifications.email-custom');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
