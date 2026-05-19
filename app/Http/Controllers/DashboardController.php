<?php

namespace App\Http\Controllers;

use App\Models\Culte;
use App\Models\Member;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    private static array $moisFr = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',
    ];
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
            'permanent_members_count' => Member::where('type', 'permanent')->count(),
            'invite_members_count' => Member::where('type', 'invite')->count(),
            'last_culte' => $lastCultePasse,
        ];

        // Récupérer les membres dont c'est l'anniversaire aujourd'hui
        $today = now()->format('d/m');
        $birthdayMembers = Member::where('type', 'permanent')
            ->whereNotNull('anniversaire_jour_mois')
            ->where('anniversaire_jour_mois', $today)
            ->get();

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

        return view('dashboard.index', compact('stats', 'recentCultes', 'birthdayMembers'));
    }

    public function absentsMensuels(Request $request)
    {
        $mois  = (int) $request->input('mois', now()->month);
        $annee = (int) $request->input('annee', now()->year);

        [$cultes, $membresAbsents, $totalPermanents] = $this->buildAbsentsData($mois, $annee);

        $moisLabel = (self::$moisFr[$mois] ?? $mois) . ' ' . $annee;

        return view('dashboard.absents-mensuels', compact('cultes', 'membresAbsents', 'mois', 'annee', 'moisLabel', 'totalPermanents'));
    }

    public function absentsMensuelsPDF(Request $request)
    {
        $mois  = (int) $request->input('mois', now()->month);
        $annee = (int) $request->input('annee', now()->year);

        [$cultes, $membresAbsents, $totalPermanents] = $this->buildAbsentsData($mois, $annee);

        $moisLabel = (self::$moisFr[$mois] ?? $mois) . ' ' . $annee;

        $pdf = Pdf::loadView('dashboard.absents-mensuels-pdf', compact('cultes', 'membresAbsents', 'mois', 'annee', 'moisLabel', 'totalPermanents'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("rapport-absents-{$annee}-{$mois}.pdf");
    }

    private function buildAbsentsData(int $mois, int $annee): array
    {
        $cultes = Culte::query()
            ->whereYear('date', $annee)
            ->whereMonth('date', $mois)
            ->whereDate('date', '<', now())
            ->orderBy('date')
            ->orderBy('heure')
            ->get();

        $culteIds    = $cultes->pluck('id');
        $totalCultes = $cultes->count();

        $members = Member::with(['category', 'attendances' => function ($q) use ($culteIds) {
            $q->whereIn('culte_id', $culteIds);
        }])
            ->where('type', 'permanent')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $totalPermanents = $members->count();

        $membresAbsents = $members->map(function ($member) use ($cultes, $totalCultes) {
            $attendancesByCulteId = $member->attendances->keyBy('culte_id');

            $cultesAbsents = $cultes->filter(function ($culte) use ($attendancesByCulteId) {
                $att = $attendancesByCulteId->get($culte->id);
                return $att === null || !$att->status;
            });

            $nbAbsences  = $cultesAbsents->count();
            $nbPresences = $totalCultes - $nbAbsences;

            return [
                'member'         => $member,
                'nb_absences'    => $nbAbsences,
                'nb_presences'   => $nbPresences,
                'taux_presence'  => $totalCultes > 0 ? round(($nbPresences / $totalCultes) * 100) : 0,
                'dates_absences' => $cultesAbsents->map(fn($c) => [
                    'date' => $c->date->format('d/m'),
                    'name' => $c->name,
                ])->values(),
            ];
        })
            ->filter(fn($d) => $d['nb_absences'] > 0)
            ->sortByDesc('nb_absences')
            ->values();

        return [$cultes, $membresAbsents, $totalPermanents];
    }
}
