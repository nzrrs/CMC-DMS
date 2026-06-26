<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentGroup extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $casts = [];
    public function documentTypes()
    {
        return $this->belongsToMany(
            DocumentType::class,
            'document_groups_map'
        );
    }
}
