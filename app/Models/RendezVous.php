<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $statut
 * @property string $nom
 * @property string $prenom
 * @property string $contact
 * @property string|null $lieu_habitation
 * @property string $motif
 * @property string $pasteur
 * @property \Illuminate\Support\Carbon $date_souhaitee
 * @property string|null $heure_souhaitee
 * @property string|null $message
 * @property string|null $notes_admin
 */
class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'nom',
        'prenom',
        'contact',
        'lieu_habitation',
        'motif',
        'pasteur',
        'date_souhaitee',
        'heure_souhaitee',
        'message',
        'statut',
        'notes_admin',
    ];

    protected $casts = [
        'date_souhaitee' => 'date',
    ];

    public const PASTEURS = [
        'Révérende GNEPE Débora',
        'Prophétesse KOUADIO Salimata',
        'Pasteur Kedi Espérance',
        'Pasteur KOUADIO Amani Paulin',
        'Prophète BAH Cédric',
        'Autre',
    ];

    public const MOTIFS = [
        'Counseling spirituel',
        'Prière personnelle',
        'Préparation au baptême',
        'Conseil matrimonial',
        'Conseil familial',
        'Suivi pastoral',
        'Demande de document',
        'Autre',
    ];

    public const STATUTS = [
        'en_attente' => 'En attente',
        'confirme'   => 'Confirmé',
        'termine'    => 'Terminé',
        'annule'     => 'Annulé',
    ];

    public function statutLibelle(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    public function statutBadgeClass(): string
    {
        return match($this->statut) {
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'confirme'   => 'bg-blue-100 text-blue-800',
            'termine'    => 'bg-green-100 text-green-800',
            'annule'     => 'bg-red-100 text-red-800',
            default      => 'bg-gray-100 text-gray-800',
        };
    }

    public function statutBorderClass(): string
    {
        return match($this->statut) {
            'en_attente' => 'border-yellow-400 bg-yellow-50',
            'confirme'   => 'border-blue-400 bg-blue-50',
            'termine'    => 'border-green-400 bg-green-50',
            'annule'     => 'border-red-400 bg-red-50',
            default      => 'border-gray-400 bg-gray-50',
        };
    }
}
