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
        'category_id',
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
}
