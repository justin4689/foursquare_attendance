<?php

namespace App\Http\Controllers;

use App\Models\Culte;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Flasher\Toastr\Prime\ToastrInterface;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CulteController extends Controller
{
    protected $toastr;

    public function __construct(ToastrInterface $toastr)
    {
        $this->toastr = $toastr;
    }

    public function index()
    {
        $cultes = Culte::orderBy('date', 'asc')
                      ->orderBy('heure', 'asc')
                      ->paginate(10);
        
        return view('cultes.index', compact('cultes'));
    }

    public function create()
    {
        return view('cultes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'heure' => 'nullable|date_format:H:i',
            'fin' => 'nullable|date_format:H:i|after:heure',
        ]);

        $culte = Culte::create($validated);

        $this->toastr->success("Le culte \"{$culte->name}\" a été créé avec succès !");
        return redirect()->route('cultes.index');
    }

    public function show(Culte $culte)
    {
        if ($culte->statut === 'passé') {
            $this->finaliserAbsentsSiCultePasse($culte);
        }

        $firstCulteOfDay = Culte::query()
            ->whereDate('date', $culte->date)
            ->orderBy('heure', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        $isFirstCulteOfDay = ($firstCulteOfDay?->id ?? null) === $culte->id;

        $attendances = $culte->attendances()->with('member.category')->get();
        $present = $attendances->where('status', true);
        $absent = $attendances->where('status', false)->filter(fn($a) => $a->member->type === 'permanent');

        $statsByCategory = $present->groupBy(fn($a) => $a->member->category->name ?? 'NC')->map->count();
        $presentGuests = $present->filter(fn($a) => $a->member->type === 'invite');

        return view('cultes.show', compact('culte', 'present', 'absent', 'statsByCategory', 'presentGuests', 'isFirstCulteOfDay'));
    }

    private function finaliserAbsentsSiCultePasse(Culte $culte): void
    {
        $existingMemberIds = Attendance::where('culte_id', $culte->id)->pluck('member_id');
     $missingMemberIds = Member::where('type', 'permanent')
    ->whereNotIn('id', $existingMemberIds)
    ->pluck('id');

        if ($missingMemberIds->isEmpty()) {
            return;
        }

        $now = now();
        $rows = $missingMemberIds->map(fn ($memberId) => [
            'culte_id' => $culte->id,
            'member_id' => $memberId,
            'status' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        DB::table('attendances')->insert($rows);
    }

    public function edit(Culte $culte)
    {
        if (in_array($culte->statut, ['en_cours', 'passé'])) {
            $this->toastr->error('Impossible de modifier : ce culte est en cours ou déjà passé.');
            return redirect()->route('cultes.index');
        }

        return view('cultes.edit', compact('culte'));
    }

    public function update(Request $request, Culte $culte)
    {
        if (in_array($culte->statut, ['en_cours', 'passé'])) {
            $this->toastr->error('Impossible de modifier : ce culte est en cours ou déjà passé.');
            return redirect()->route('cultes.index');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'heure' => 'nullable|date_format:H:i',
            'fin' => 'nullable|date_format:H:i|after:heure',
        ]);

        $culte->update($validated);

        $this->toastr->success("Le culte \"{$culte->name}\" a été mis à jour avec succès !");
        return redirect()->route('cultes.index');
    }

    public function destroy(Culte $culte)
    {
        if (in_array($culte->statut, ['en_cours'])) {
            $this->toastr->error('Impossible de supprimer : ce culte est en cours.');
            return redirect()->route('cultes.index');
        }

        $attendancesCount = $culte->attendances()->count();
        $culteName = $culte->name;
        
        if ($attendancesCount > 0) {
            // Supprimer d'abord les présences
            $culte->attendances()->delete();
        }
        
        // Puis supprimer le culte
        $culte->delete();
        
        if ($attendancesCount > 0) {
            $this->toastr->success("Le culte '{$culteName}' et ses {$attendancesCount} présence(s) ont été supprimés avec succès !");
        } else {
            $this->toastr->success("Le culte '{$culteName}' a été supprimé avec succès !");
        }
        
        return redirect()->route('cultes.index');
    }

    public function pointage(Culte $culte)
    {
        // Vérifier si le culte est disponible pour le pointage
        if ($culte->date->lt(now()->startOfDay())) {
            $this->toastr->error('Ce culte est déjà passé, impossible de pointer.');
            return redirect()->route('cultes.index');
        }

        return view('cultes.pointage', compact('culte'));
    }

    public function editPresence(Culte $culte)
    {
        $members = Member::with('category')->get();
        $attendances = $culte->attendances()->with('member')->get();
        $attendanceByMemberId = $attendances->keyBy('member_id');

        return view('attendances.edit', compact('culte', 'members', 'attendanceByMemberId'));
    }

    public function updatePresence(Request $request, Culte $culte)
    {
        $validated = $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|boolean',
        ]);

        foreach ($validated['status'] as $memberId => $status) {
            Attendance::updateOrCreate(
                ['culte_id' => $culte->id, 'member_id' => $memberId],
                ['status' => $status]
            );
        }

        $this->toastr->success('Pointage enregistré avec succès !');
        return redirect()->route('cultes.show', $culte);
    }

    public function generatePDF(Culte $culte)
    {
        // Finaliser les absents si le culte est passé
        if ($culte->statut === 'passé') {
            $this->finaliserAbsentsSiCultePasse($culte);
        }

        $attendances = $culte->attendances()->with('member.category')->get();
        $present = $attendances->where('status', true);
        $absent = $attendances->where('status', false)->filter(fn($a) => $a->member->type === 'permanent');

        $dayCulteIds = Culte::query()
            ->whereDate('date', $culte->date)
            ->pluck('id');

        $presentMemberIdsToday = Attendance::query()
            ->whereIn('culte_id', $dayCulteIds)
            ->where('status', true)
            ->pluck('member_id')
            ->unique()
            ->all();

        $presentInDayByMemberId = $absent
            ->mapWithKeys(fn($a) => [$a->member_id => in_array($a->member_id, $presentMemberIdsToday, true)])
            ->all();

        $statsByCategory = $present->groupBy(fn($a) => $a->member->category->name ?? 'NC')->map->count();
        $presentGuests = $present->filter(fn($a) => $a->member->type === 'invite');

        $data = [
            'culte' => $culte,
            'present' => $present,
            'absent' => $absent,
            'presentInDayByMemberId' => $presentInDayByMemberId,
            'statsByCategory' => $statsByCategory,
            'totalPresent' => $present->count(),
            'totalAbsent' => $absent->count(),
            'totalMembers' => $attendances->count(),
            'totalGuests' => $presentGuests->count(),
        ];

        $pdf = PDF::loadView('cultes.pdf', $data);
        
        return $pdf->download("rapport-culte-{$culte->id}.pdf");
    }

    public function generateDailyPDF(Culte $culte)
    {
        $dayCultes = Culte::query()
            ->whereDate('date', $culte->date)
            ->orderBy('heure', 'asc')
            ->get();

        $dayCulteIds = $dayCultes->pluck('id');

        $attendances = Attendance::query()
            ->whereIn('culte_id', $dayCulteIds)
            ->with('member.category')
            ->get();

        $presentMemberIds = $attendances
            ->where('status', true)
            ->pluck('member_id')
            ->unique();

        $members = Member::with('category')->get();

        $presentMembers = $members
            ->filter(fn($m) => $presentMemberIds->contains($m->id));

        $presentPermanentMembers = $presentMembers
            ->filter(fn($m) => $m->type === 'permanent')
            ->values();

        $presentGuestMembers = $presentMembers
            ->filter(fn($m) => $m->type === 'invite')
            ->values();

        $absentMembers = $members
            ->filter(fn($m) => $m->type === 'permanent')
            ->filter(fn($m) => !$presentMemberIds->contains($m->id));

        $statsByCategory = $presentMembers
            ->filter(fn($m) => $m->type === 'permanent')
            ->groupBy(fn($m) => $m->category->name ?? 'NC')
            ->map(fn($g) => $g->count());

        $totalGuests = $presentGuestMembers->count();

        $data = [
            'date' => $culte->date,
            'cultes' => $dayCultes,
            'presentMembers' => $presentMembers,
            'presentPermanentMembers' => $presentPermanentMembers,
            'presentGuestMembers' => $presentGuestMembers,
            'absentMembers' => $absentMembers,
            'statsByCategory' => $statsByCategory,
            'totalPresent' => $presentMembers->count(),
            'totalAbsent' => $absentMembers->count(),
            'totalGuests' => $totalGuests,
        ];

        $pdf = PDF::loadView('cultes.pdf-journalier', $data);

        $dateStr = ($culte->date?->format('Y-m-d')) ?? \Carbon\Carbon::parse($culte->date)->format('Y-m-d');
        return $pdf->download("rapport-journalier-{$dateStr}.pdf");
    }
}
