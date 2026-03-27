<?php

namespace App\Console\Commands;

use App\Models\LoginLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupLoginLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'login-logs:cleanup {--dry-run : Afficher les doublons sans les supprimer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les doublons dans les logs de connexion';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des doublons dans les logs de connexion...');

        // Trouver les doublons basés sur user_id, action, ip_address et la même minute
        $duplicates = DB::table('login_logs')
            ->select('user_id', 'action', 'ip_address', 
                    DB::raw('DATE_FORMAT(logged_at, "%Y-%m-%d %H:%i") as minute'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'action', 'ip_address', DB::raw('DATE_FORMAT(logged_at, "%Y-%m-%d %H:%i")'))
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('Aucun doublon trouvé.');
            return 0;
        }

        $this->info("{$duplicates->count()} groupe(s) de doublons trouvé(s).");

        $totalToDelete = 0;
        foreach ($duplicates as $duplicate) {
            $idsToDelete = DB::table('login_logs')
                ->where('user_id', $duplicate->user_id)
                ->where('action', $duplicate->action)
                ->where('ip_address', $duplicate->ip_address)
                ->where('logged_at', 'LIKE', $duplicate->minute . '%')
                ->where('id', '!=', $duplicate->keep_id)
                ->pluck('id');

            $countToDelete = $idsToDelete->count();
            $totalToDelete += $countToDelete;

            $this->line("Utilisateur {$duplicate->user_id} - {$duplicate->action} - {$duplicate->ip_address} - {$duplicate->minute}: {$countToDelete} doublon(s)");
        }

        if ($this->option('dry-run')) {
            $this->warn("Mode dry-run: {$totalToDelete} doublons seraient supprimés.");
            return 0;
        }

        if ($this->confirm("Supprimer les {$totalToDelete} doublons ?")) {
            foreach ($duplicates as $duplicate) {
                DB::table('login_logs')
                    ->where('user_id', $duplicate->user_id)
                    ->where('action', $duplicate->action)
                    ->where('ip_address', $duplicate->ip_address)
                    ->where('logged_at', 'LIKE', $duplicate->minute . '%')
                    ->where('id', '!=', $duplicate->keep_id)
                    ->delete();
            }

            $this->info("{$totalToDelete} doublons ont été supprimés avec succès.");
        }

        return 0;
    }
}
