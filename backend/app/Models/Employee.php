<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'matricule',
        'grade',
        'position',
        'recruitment_date',
    ];

    protected $casts = [
        'recruitment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
