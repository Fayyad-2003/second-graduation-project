<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentApplication extends Model
{
    protected $fillable = [
        'student_id',
        'document_type_id',
        'status',
        'uploaded_files',
        'admin_notes',
        'completed_at'
    ];

    protected $casts = [
        'uploaded_files' => 'array',
        'completed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
