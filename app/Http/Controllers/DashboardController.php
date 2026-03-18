<?php

namespace App\Http\Controllers;

use App\Models\Culte;
use App\Models\Member;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        $lastCultePasse = Culte::where(function ($query) use ($now) {
                $query->whereDate('date', '<', $now->toDateString())
                      ->orWhere(function ($sub) use ($now) {
                          $sub->whereDate('date', $now->toDateString())
                              ->whereNotNull('fin')
                              ->whereTime('fin', '<', $now->format('H:i:s'));
                      });
            })
            ->orderBy('date', 'desc')
            ->orderBy('heure', 'desc')
            ->first();

        $stats = [
            'members_count' => Member::count(),
            'cultes_count' => Culte::count(),
            'categories_count' => Category::count(),
            'last_culte' => $lastCultePasse,
        ];

        $recentCultes = Culte::withCount('attendances')
            ->where(function ($query) use ($now) {
                $query->whereDate('date', '<', $now->toDateString())
                      ->orWhere(function ($sub) use ($now) {
                          $sub->whereDate('date', $now->toDateString())
                              ->whereNotNull('fin')
                              ->whereTime('fin', '<', $now->format('H:i:s'));
                      });
            })
            ->orderBy('date', 'desc')
            ->orderBy('heure', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentCultes'));
    }
}
