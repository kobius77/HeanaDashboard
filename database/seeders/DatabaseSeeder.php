<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if user exists before creating to avoid production errors
        if (! User::where('email', 'markus@photing.com')->exists()) {
            User::factory()->create([
                'name' => 'Markus',
                'email' => 'markus@photing.com',
                'password' => Hash::make('asdfasdf'),
            ]);
        }

        // Only seed logs if table is empty (prevent duplicating logs in production)
        if (\App\Models\DailyLog::count() === 0) {
            $startDate = now()->subDays(50);
            foreach (range(0, 49) as $i) {
                \App\Models\DailyLog::factory()->create([
                    'log_date' => $startDate->copy()->addDays($i)->format('Y-m-d'),
                ]);
            }
        }
    }
}
