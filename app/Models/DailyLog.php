<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DailyLog extends Model
{
    use HasFactory;

    /**
     * Scope a query to select year and month based on database driver.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $column
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectYearMonth($query, $column = 'log_date')
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return $query->selectRaw("strftime('%Y', $column) as year, strftime('%m', $column) as month");
        }

        return $query->selectRaw("YEAR($column) as year, MONTH($column) as month");
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daily_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'log_date',
        'egg_count',
        'notes',
        'weather_temp_c',
        'sun_hours',
        'reported_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'log_date' => 'date',
    ];
}
