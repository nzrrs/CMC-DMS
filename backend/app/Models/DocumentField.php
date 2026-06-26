<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentField extends Model
{
      protected $fillable = [
        'document_type_id',
        'field_key',
        'field_type',
        'source_table',
        'source_field',
        'is_required',
        'config_json',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'config_json' => 'array',
    ];
    public function documentType()
{
    return $this->belongsTo(DocumentType::class);
}
}
