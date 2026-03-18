<?php

namespace App\Http\Controllers;

use App\Models\Culte;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Flasher\Toastr\Prime\ToastrInterface;

class AttendanceController extends Controller
{
    protected $toastr;

    public function __construct(ToastrInterface $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Afficher la page de recherche et de pointage rapide
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Rechercher des membres par nom
     */
    public function search(Request $request)
    {
        $search = $request->get('search');
        $members = Member::where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->get();

        return view('welcome', compact('members'));
    }

    /**
     * Afficher le formulaire de pointage pour un membre
     */
    public function pointage(Request $request)
    {
        $memberId = $request->get('member_id');
        $member = Member::findOrFail($memberId);
        
        $now = now();
        $today = today();

        $formatInterval = function ($interval) {
            $parts = [];

            if (!empty($interval->days)) {
                $parts[] = $interval->days . ' j';
            }
            if (!empty($interval->h)) {
                $parts[] = $interval->h . ' h';
            }
            if (!empty($interval->i)) {
                $parts[] = $interval->i . ' min';
            }

            if (empty($parts)) {
                return "moins d'une minute";
            }

            return implode(' ', $parts);
        };
        
        // 1. Chercher un culte en cours (période de pointage: 1h avant début jusqu'à fin)
        $cultePointage = Culte::whereDate('date', $today)
                           ->where(function($query) use ($now) {
                               // Culte en cours maintenant
                               $query->where(function($subQuery) use ($now) {
                                   $subQuery->whereTime('heure', '<=', $now->format('H:i:s'))
                                           ->where(function($timeQuery) use ($now) {
                                               $timeQuery->whereNull('fin')
                                                        ->orWhereTime('fin', '>=', $now->format('H:i:s'));
                                           });
                               })
                               // Ou culte qui commence dans moins d'1 heure
                               ->orWhere(function($subQuery) use ($now) {
                                   $subQuery->whereTime('heure', '>', $now->format('H:i:s'))
                                           ->whereTime('heure', '<=', $now->copy()->addHour()->format('H:i:s'));
                               });
                           })
                           ->orderBy('heure', 'asc')
                           ->first();

        // 2. Calculer le message et le temps
        $message = '';
        $tempsRestant = '';
        $culteActuel = null;
        
        if ($cultePointage) {
            $culteActuel = $cultePointage;
            $heureDebut = now()->setTimeFromTimeString($cultePointage->heure);
            
            if ($now->lt($heureDebut)) {
                // Le culte n'a pas encore commencé (moins d'1h avant)
                $tempsRestant = $now->diff($heureDebut);
                $message = 'Commence dans ' . $formatInterval($tempsRestant);
            } else {
                // Le culte est en cours
                $tempsRestant = $cultePointage->fin ? 
                    now()->setTimeFromTimeString($cultePointage->fin)->diff($now) : 
                    $heureDebut->copy()->addHours(2)->diff($now);
                
                if ($tempsRestant->invert) {
                    $message = "En cours maintenant";
                } else {
                    $message = "Termine dans " . $tempsRestant->format('%i min') . " minute" . ($tempsRestant->i > 1 ? 's' : '');
                }
            }
        }

        $dejaPointe = false;
        if ($culteActuel) {
            $dejaPointe = Attendance::where('member_id', $member->id)
                ->where('culte_id', $culteActuel->id)
                ->exists();
        }
        
        return view('attendance.pointage', compact(
            'member', 
            'culteActuel',
            'message',
            'tempsRestant',
            'dejaPointe'
        ));
    }

    /**
     * Enregistrer la présence pour un membre et un culte
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'culte_id' => 'required|exists:cultes,id',
            'status' => 'required|in:0,1',
        ]);

        $alreadyExists = Attendance::where('member_id', $request->member_id)
            ->where('culte_id', $request->culte_id)
            ->exists();

        if ($alreadyExists) {
            $this->toastr->warning('Vous avez déjà pointé pour ce culte.');

            return redirect()
                ->route('attendance.pointage', ['member_id' => $request->member_id])
                ->with('pointage_already', true);
        }

        Attendance::create([
            'member_id' => $request->member_id,
            'culte_id' => $request->culte_id,
            'status' => $request->status == 1,
        ]);

        // Utiliser Flasher Toastr pour le message de succès
        $this->toastr->success('Pointage enregistré avec succès !');

        $culte = Culte::find($request->culte_id);

        return redirect()
            ->route('attendance.pointage', ['member_id' => $request->member_id])
            ->with('pointage_success', true)
            ->with('pointage_culte_name', $culte?->name)
            ->with('pointage_culte_date', $culte?->date?->format('d/m/Y'))
            ->with('pointage_culte_heure', $culte?->heure?->format('H:i'))
            ->with('pointage_culte_fin', $culte?->fin?->format('H:i'));
    }
}
