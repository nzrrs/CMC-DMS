<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'template_name',
        'template_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function fields()
    {
        return $this->hasMany(DocumentField::class);
    }

    public function generatedDocuments()
    {
        return $this->hasMany(GeneratedDocument::class);
    }

    public function groups()
    {
        return $this->belongsToMany(
            DocumentGroup::class,
            'document_groups_map'
        );
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_document_types');
    }


}
