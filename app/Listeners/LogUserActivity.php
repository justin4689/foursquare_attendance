<?php

namespace App\Listeners;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Request;

class LogUserActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the login event.
     */
    public function handleLogin(Login $event): void
    {
        // Ne logger que les agents
        $user = $event->user;
        if ($user instanceof User && $user->role === 'agent') {
            // Créer un identifiant unique pour éviter les doublons
            $uniqueId = md5($user->id . 'login' . Request::ip() . now()->format('Y-m-d H:i'));
            
            // Vérifier si ce log exact existe déjà
            $existingLog = LoginLog::where('user_id', $user->id)
                ->where('action', 'login')
                ->where('ip_address', Request::ip())
                ->where('logged_at', '>=', now()->subMinutes(1))
                ->first();

            if (!$existingLog) {
                LoginLog::create([
                    'user_id' => $user->id,
                    'action' => 'login',
                    'ip_address' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                    'logged_at' => now(),
                ]);
            }
        }
    }

    /**
     * Handle the logout event.
     */
    public function handleLogout(Logout $event): void
    {
        // Ne logger que les agents
        $user = $event->user;
        if ($user instanceof User && $user->role === 'agent') {
            // Créer un identifiant unique pour éviter les doublons
            $uniqueId = md5($user->id . 'logout' . Request::ip() . now()->format('Y-m-d H:i'));
            
            // Vérifier si ce log exact existe déjà
            $existingLog = LoginLog::where('user_id', $user->id)
                ->where('action', 'logout')
                ->where('ip_address', Request::ip())
                ->where('logged_at', '>=', now()->subMinutes(1))
                ->first();

            if (!$existingLog) {
                LoginLog::create([
                    'user_id' => $user->id,
                    'action' => 'logout',
                    'ip_address' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                    'logged_at' => now(),
                ]);
            }
        }
    }
}
