<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    /** @use HasFactory<\Database\Factories\GeneralSettingFactory> */
    use HasFactory;

    protected $fillable = ['key', 'value'];
}
