<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'type',
        'category_id',
        'lieu_habitation',
        'anniversaire_jour_mois',
        'phone',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function estPermanent(): bool
    {
        return $this->type === 'permanent';
    }

    public function estInvite(): bool
    {
        return $this->type === 'invite';
    }
}
