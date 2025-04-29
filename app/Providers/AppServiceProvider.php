<?php

namespace App\Providers;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Confirmation de votre adresse email')
                ->line('Bienvenue sur E-ticket. Nous sommes heureux de vous compter parmi nous. Pour finaliser votre inscription, veuillez cliquer ci-dessous.')
                ->action('Confirmer mon email', $url)
                ->line('Si vous n’avez pas créé de compte, ignorez ce message.');
        });
}
}