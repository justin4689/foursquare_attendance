<?php

namespace App\Http\Controllers;

use App\Models\Culte;
use Illuminate\Http\Request;

class PointageController extends Controller
{
    public function intelligent()
    {
        $now = now();
        $today = today();
        
        // 1. Chercher un culte en cours maintenant
        $culteActuel = Culte::whereDate('date', $today)
                           ->where(function($query) use ($now) {
                               $query->whereTime('heure', '<=', $now->format('H:i:s'))
                                     ->where(function($subQuery) use ($now) {
                                         $subQuery->whereNull('fin')
                                                  ->orWhereTime('fin', '>=', $now->format('H:i:s'));
                                     });
                           })
                           ->first();
        
        // 2. Chercher le prochain culte aujourd'hui
        $prochainCulteAujourdhui = null;
        if (!$culteActuel) {
            $prochainCulteAujourdhui = Culte::whereDate('date', $today)
                                          ->whereTime('heure', '>', $now->format('H:i:s'))
                                          ->orderBy('heure', 'asc')
                                          ->first();
        }
        
        // 3. Prochains cultes (pour les autres options)
        $prochainsCultes = Culte::where('date', '>', $today)
                                  ->orWhere(function($query) use ($today, $now) {
                                      $query->whereDate('date', $today)
                                            ->whereTime('heure', '>', $now->format('H:i:s'));
                                  })
                                  ->orderBy('date', 'asc')
                                  ->orderBy('heure', 'asc')
                                  ->limit(5)
                                  ->get();
        
        return view('pointage.intelligent', compact(
            'culteActuel', 
            'prochainCulteAujourdhui', 
            'prochainsCultes'
        ));
    }
}
