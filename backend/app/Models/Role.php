<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $casts = [];
    public function users()
{
    return $this->hasMany(User::class);
}

public function documentTypes()
{
    return $this->belongsToMany(DocumentType::class, 'role_document_types');
}
}
