<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'culte_id',
        'member_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function culte()
    {
        return $this->belongsTo(Culte::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
