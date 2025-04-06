<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Verify Your Email Address')
                    ->line('Please click the button below to verify your email address.')
                    ->action('Verify Email Address', url('/verify-email/'.$notifiable->verification_token))
                    ->line('If you did not create an account, no further action is required.');
    }
}