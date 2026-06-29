<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'matricule',
        'phone',
        'grade',
        'position',
        'recruitment_date',
    ];

    protected $casts = [
        'recruitment_date' => 'date:d/m/Y',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
