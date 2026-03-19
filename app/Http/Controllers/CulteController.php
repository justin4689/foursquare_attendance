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

        $attendances = $culte->attendances()->with('member.category')->get();
        $present = $attendances->where('status', true);
        $absent = $attendances->where('status', false);

        $statsByCategory = $present->groupBy(fn($a) => $a->member->category->name ?? 'NC')->map->count();

        return view('cultes.show', compact('culte', 'present', 'absent', 'statsByCategory'));
    }

    private function finaliserAbsentsSiCultePasse(Culte $culte): void
    {
        $existingMemberIds = Attendance::where('culte_id', $culte->id)->pluck('member_id');
        $missingMemberIds = Member::whereNotIn('id', $existingMemberIds)->pluck('id');

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
        if (in_array($culte->statut, ['en_cours', 'passé'])) {
            $this->toastr->error('Impossible de supprimer : ce culte est en cours ou déjà passé.');
            return redirect()->route('cultes.index');
        }

        $culte->delete();
        $this->toastr->success('Culte supprimé avec succès !');
        return redirect()->route('cultes.index');
    }

    public function pointage(Culte $culte)
    {
        // Vérifier si le culte est disponible pour le pointage
        if ($culte->date->isPast()) {
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
        $absent = $attendances->where('status', false);
        $statsByCategory = $present->groupBy(fn($a) => $a->member->category->name ?? 'NC')->map->count();

        $data = [
            'culte' => $culte,
            'present' => $present,
            'absent' => $absent,
            'statsByCategory' => $statsByCategory,
            'totalPresent' => $present->count(),
            'totalAbsent' => $absent->count(),
            'totalMembers' => $attendances->count(),
        ];

        $pdf = PDF::loadView('cultes.pdf', $data);
        
        return $pdf->download("rapport-culte-{$culte->id}.pdf");
    }
}
