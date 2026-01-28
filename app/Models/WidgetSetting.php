<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'widget_class',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}