<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class LoginLogController extends Controller
{
    public function index(Request $request)
    {
        // Seuls les administrateurs peuvent voir les logs
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $query = LoginLog::with('user')->forAgents()->latest('logged_at');

        // Filtrage par utilisateur
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filtrage par action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filtrage par date
        if ($request->has('date') && $request->date) {
            $query->whereDate('logged_at', $request->date);
        }

        $loginLogs = $query->paginate(50);
        
        // Récupérer la liste des agents pour le filtre
        $agents = \App\Models\User::where('role', 'agent')->orderBy('name')->get();

        return view('login-logs.index', compact('loginLogs', 'agents'));
    }
}
