<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactMessageNotification extends Notification
{
    use Queueable;

    public string $name;
    public string $email;
    public string $message;

    public function __construct(string $name, string $email, string $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("Message de contact de {$this->name}")
                    ->replyTo($this->email)
                    ->line("Nom : {$this->name}")
                    ->line("Email : {$this->email}")
                    ->line("Message :")
                    ->line($this->message);
    }
}
