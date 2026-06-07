<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentNotification extends Model
{
    protected $fillable = [
        'sender_id',
        'title',
        'message',
        'target_type',
        'target_id',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
