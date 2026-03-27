<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\LogUserActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\URL;


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
        // Enregistrer les écouteurs d'événements pour les logs de connexion
        Event::listen(Login::class, [LogUserActivity::class, 'handleLogin']);
        Event::listen(Logout::class, [LogUserActivity::class, 'handleLogout']);

        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
