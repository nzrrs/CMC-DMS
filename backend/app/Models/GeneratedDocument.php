<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedDocument extends Model
{

    protected $fillable = [
        'user_id',
        'document_type_id',
        'file_name',
        'file_path',
        'version',
        'status',
    ];

    protected $casts = [
        'version' => 'integer',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
