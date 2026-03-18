<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Culte extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'heure',
        'fin',
    ];

    protected $casts = [
        'date' => 'date',
        'heure' => 'datetime:H:i',
        'fin' => 'datetime:H:i',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'attendances')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Scope pour les cultes d'aujourd'hui
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date', today());
    }

    // Scope pour les cultes à venir
    public function scopeAVenir($query)
    {
        return $query->whereDate('date', '>=', today());
    }

    // Scope pour les cultes passés
    public function scopePasses($query)
    {
        return $query->whereDate('date', '<', today());
    }

    // Vérifier si le culte est en cours
    public function estEnCours()
    {
        if (!$this->heure) {
            return false;
        }
        
        $now = now();
        $culteStart = now()->setTimeFromTimeString($this->heure);
        
        // Si pas d'heure de fin, on considère 2h de durée par défaut
        if ($this->fin) {
            $culteEnd = now()->setTimeFromTimeString($this->fin);
        } else {
            $culteEnd = $culteStart->copy()->addHours(2);
        }
        
        return $now->between($culteStart, $culteEnd);
    }

    // Vérifier si le culte est à venir aujourd'hui
    public function estAVenirAujourdhui()
    {
        if (!$this->heure) {
            return false;
        }
        
        return $this->date->isToday() && 
               now()->lt(now()->setTimeFromTimeString($this->heure));
    }

    // Obtenir le statut du culte
    public function getStatutAttribute()
    {
        $now = now();
        
        // Si le culte est dans le futur (date future)
        if ($this->date->isFuture()) {
            return 'à_venir';
        }
        
        // Si le culte est aujourd'hui
        if ($this->date->isToday()) {
            // Si l'heure de fin est passée, le culte est terminé
            if ($this->fin && $now->gt(now()->setTimeFromTimeString($this->fin))) {
                return 'passé';
            }
            
            // Si le culte est en cours
            if ($this->estEnCours()) {
                return 'en_cours';
            }
            
            // Si l'heure de début est passée mais pas la fin (en cours)
            if ($this->heure && $now->gte(now()->setTimeFromTimeString($this->heure))) {
                return 'en_cours';
            }
            
            // Si l'heure de début n'est pas encore passée
            return 'aujourd_hui';
        }
        
        // Si le culte est dans le passé (date passée)
        return 'passé';
    }

    // Obtenir le libellé du statut
    public function getStatutLibelleAttribute()
    {
        return match($this->statut) {
            'en_cours' => "🟡 En cours",
            'aujourd_hui' => "🔵 Aujourd'hui",
            'à_venir' => "🔵 À venir",
            'passé' => "⚪ Passé",
            default => "⚪ Inconnu"
        };
    }

    // Obtenir le nombre de présents
    public function getNbPresencesAttribute()
    {
        return $this->attendances()->where('status', true)->count();
    }

    // Obtenir le nombre d'absents
    public function getNbAbsencesAttribute()
    {
        return $this->attendances()->where('status', false)->count();
    }
}
