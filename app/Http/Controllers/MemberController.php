<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Category;
use Illuminate\Http\Request;
use Flasher\Toastr\Prime\ToastrInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberController extends Controller
{
    protected $toastr;

    public function __construct(ToastrInterface $toastr)
    {
        $this->toastr = $toastr;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $permanentsQuery = Member::with('category')->where('type', 'permanent');
        $invitesQuery = Member::with('category')->where('type', 'invite');
        
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $permanentsQuery->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                  ->orWhere('last_name', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm);
            });
            
            $invitesQuery->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                  ->orWhere('last_name', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm);
            });
        }
        
        $permanents = $permanentsQuery->paginate(20);
        $invites = $invitesQuery->paginate(20);
        $permanentsCount = Member::where('type', 'permanent')->count();
        $invitesCount = Member::where('type', 'invite')->count();
        
        return view('members.index', compact('permanents', 'invites', 'permanentsCount', 'invitesCount', 'search'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('members.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'type' => 'required|in:permanent,invite',
            'category_id' => 'nullable|exists:categories,id',
            'phone' => 'nullable|string|max:20',
            'lieu_habitation' => 'nullable|string|max:255',
            'anniversaire_jour_mois' => ['nullable', 'regex:/^\d{2}\/\d{2}$/'],
        ]);

        if (($validated['type'] ?? null) !== 'permanent') {
            $validated['lieu_habitation'] = null;
            $validated['anniversaire_jour_mois'] = null;
        }

        $member = Member::create($validated);

        $this->toastr->success("Le membre {$member->first_name} {$member->last_name} a été ajouté avec succès !");

        return redirect()->route('members.index');
    }

    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'type' => 'required|in:permanent,invite',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|max:20',
            'lieu_habitation' => 'required_if:type,permanent|nullable|string|max:255',
            'anniversaire_jour_mois' => ['required_if:type,permanent', 'nullable', 'regex:/^\d{2}\/\d{2}$/'],
        ]);

        if (($validated['type'] ?? null) !== 'permanent') {
            $validated['lieu_habitation'] = null;
            $validated['anniversaire_jour_mois'] = null;
        }

        $member = Member::create($validated);

        $this->toastr->success("Bienvenue {$member->first_name} {$member->last_name} ! Inscription réussie.");

        return redirect()->route('attendance.index');
    }

    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $categories = Category::all();
        return view('members.edit', compact('member', 'categories'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'type' => 'required|in:permanent,invite',
            'category_id' => 'nullable|exists:categories,id',
            'phone' => 'nullable|string|max:20',
            'lieu_habitation' => 'nullable|string|max:255',
            'anniversaire_jour_mois' => ['nullable', 'regex:/^\d{2}\/\d{2}$/'],
        ]);

        if (($validated['type'] ?? null) !== 'permanent') {
            $validated['lieu_habitation'] = null;
            $validated['anniversaire_jour_mois'] = null;
        }

        $member->update($validated);

        $this->toastr->success("Le membre {$member->first_name} {$member->last_name} a été mis à jour avec succès !");

        return redirect()->route('members.index');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        $this->toastr->success('Membre supprimé avec succès !');
        return redirect()->route('members.index');
    }

    public function exportPDF(Request $request)
    {
        $type = $request->get('type', 'permanents');
        
        $members = Member::with('category')
            ->where('type', $type === 'invites' ? 'invite' : 'permanent')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $title = $type === 'invites' ? 'Liste des Invités' : 'Liste des Membres Permanents';
        
        $data = [
            'title' => $title,
            'members' => $members,
            'type' => $type,
            'exported_at' => now()->format('d/m/Y à H:i')
        ];

        $pdf = Pdf::loadView('members.pdf', $data);
        
        $filename = $type === 'invites' ? 'invites-' . now()->format('Y-m-d') : 'permanents-' . now()->format('Y-m-d');
        
        return $pdf->download($filename . '.pdf');
    }
}
