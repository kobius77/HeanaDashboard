<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlockRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_date',
        'ovulating_hens',
        'henopaused_hens',
        'cock',
        'chicklets',
        'notes',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];
}
