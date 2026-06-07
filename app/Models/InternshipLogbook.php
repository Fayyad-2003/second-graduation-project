<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipLogbook extends Model
{
    use HasFactory;

    protected $table = 'internship_logbooks';

    protected $fillable = [
        'internship_id',
        'date',
        'entry_time',
        'exit_time',
        'activity',
        'supervisor_notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REVISION = 'revision';

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'amber',
            self::STATUS_APPROVED => 'emerald',
            self::STATUS_REVISION => 'red',
            default => 'slate'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_REVISION => __('Revision'),
            default => $this->status
        };
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
