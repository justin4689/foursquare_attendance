<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;
use Flasher\Toastr\Prime\ToastrInterface;

class RendezVousController extends Controller
{
    protected $toastr;

    public function __construct(ToastrInterface $toastr)
    {
        $this->toastr = $toastr;
    }

    public function index(Request $request)
    {
        $statut = $request->get('statut', '');
        $pasteur = $request->get('pasteur', '');

        $query = RendezVous::orderBy('date_souhaitee', 'asc')->orderBy('created_at', 'desc');

        if (!empty($statut)) {
            $query->where('statut', $statut);
        }

        if (!empty($pasteur)) {
            $query->where('pasteur', $pasteur);
        }

        $rendezVous = $query->paginate(20)->withQueryString();
        $pasteurs = RendezVous::PASTEURS;
        $statuts = RendezVous::STATUTS;

        $counts = [
            'en_attente' => RendezVous::where('statut', 'en_attente')->count(),
            'confirme'   => RendezVous::where('statut', 'confirme')->count(),
            'termine'    => RendezVous::where('statut', 'termine')->count(),
            'annule'     => RendezVous::where('statut', 'annule')->count(),
        ];

        return view('rendez-vous.index', compact('rendezVous', 'pasteurs', 'statuts', 'counts', 'statut', 'pasteur'));
    }

    public function create()
    {
        $pasteurs = RendezVous::PASTEURS;
        $motifs = RendezVous::MOTIFS;
        return view('rendez-vous.public', compact('pasteurs', 'motifs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'            => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'contact'        => 'required|string|max:20',
            'lieu_habitation'=> 'nullable|string|max:255',
            'motif'          => 'required|string|max:255',
            'pasteur'        => 'required|string|in:' . implode(',', RendezVous::PASTEURS),
            'date_souhaitee' => 'required|date|after_or_equal:today',
            'heure_souhaitee'=> 'nullable|date_format:H:i',
            'message'        => 'nullable|string|max:1000',
        ], [
            'nom.required'            => 'Le nom est obligatoire.',
            'prenom.required'         => 'Le prénom est obligatoire.',
            'contact.required'        => 'Le contact est obligatoire.',
            'motif.required'          => 'Le motif est obligatoire.',
            'pasteur.required'        => 'Veuillez choisir un pasteur.',
            'pasteur.in'              => 'Le pasteur sélectionné est invalide.',
            'date_souhaitee.required' => 'La date souhaitée est obligatoire.',
            'date_souhaitee.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
        ]);

        RendezVous::create($validated);

        return redirect()->route('rendez-vous.confirmation');
    }

    public function confirmation()
    {
        return view('rendez-vous.confirmation');
    }

    public function show(RendezVous $rendezVou)
    {
        $statuts = RendezVous::STATUTS;
        return view('rendez-vous.show', compact('rendezVou', 'statuts'));
    }

    public function updateStatut(Request $request, RendezVous $rendezVou)
    {
        $validated = $request->validate([
            'statut'      => 'required|in:en_attente,confirme,termine,annule',
            'notes_admin' => 'nullable|string|max:1000',
        ]);

        $rendezVou->update($validated);

        $this->toastr->success('Statut mis à jour avec succès.');
        return redirect()->route('rendez-vous.show', $rendezVou);
    }

    public function destroy(RendezVous $rendezVou)
    {
        $nom = "{$rendezVou->prenom} {$rendezVou->nom}";
        $rendezVou->delete();

        $this->toastr->success("Le rendez-vous de {$nom} a été supprimé.");
        return redirect()->route('rendez-vous.index');
    }
}
