<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpaCreditRule extends Model
{
    use HasFactory;

    protected $table = 'gpa_credit_rules';

    protected $fillable = [
        'label',
        'min_gpa',
        'max_gpa',
        'max_credits',
    ];

    protected $casts = [
        'min_gpa' => 'float',
        'max_gpa' => 'float',
        'max_credits' => 'integer',
    ];
}
