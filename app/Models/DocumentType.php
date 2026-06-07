<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = ['name', 'description', 'required_files', 'is_active'];

    protected $casts = [
        'required_files' => 'array',
        'is_active' => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(DocumentApplication::class);
    }
}
