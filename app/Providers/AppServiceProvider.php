<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
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
        // Correo de "olvidé mi contraseña" en español
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = route('password.reset', ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()]);

            return (new MailMessage)
                ->subject('Restablecer contraseña · '.config('app.name'))
                ->greeting('Hola, '.$notifiable->name)
                ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
                ->action('Restablecer contraseña', $url)
                ->line('Este enlace vence en '.config('auth.passwords.users.expire').' minutos.')
                ->line('Si no solicitaste el cambio, puedes ignorar este correo.')
                ->salutation('Saludos, '.config('app.name'));
        });
    }
}
