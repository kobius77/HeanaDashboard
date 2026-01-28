<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Ingest data via webhook.
     *
     * Expected Headers:
     * - X-Webhook-Secret: <your-secret>
     *
     * Expected Payload (JSON):
     * {
     *   "date": "2026-01-29", // Optional, defaults to today
     *   "sun_hours": 5.5,
     *   "weather_temp_c": 12.0,
     *   "egg_count": 10,
     *   "notes": "..."
     * }
     */
    public function ingest(Request $request): JsonResponse
    {
        // 1. Verify Secret
        $secret = config('services.webhook.secret');
        if (! $secret || $request->header('X-Webhook-Secret') !== $secret) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 2. Validate
        $validated = $request->validate([
            'date' => 'nullable|date',
            'sun_hours' => 'nullable|numeric',
            'weather_temp_c' => 'nullable|numeric',
            'egg_count' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $date = $validated['date'] ?? now()->toDateString();

        // 3. Find or Create Log
        $log = DailyLog::firstOrCreate(
            ['log_date' => $date],
            [] // Create with empty attributes if not found, we update below
        );

        // 4. Update allowed fields if present in request
        $allowedFields = ['sun_hours', 'weather_temp_c', 'egg_count', 'notes'];
        $updates = [];

        foreach ($allowedFields as $field) {
            if ($request->has($field)) {
                $updates[$field] = $request->input($field);
            }
        }

        if (! empty($updates)) {
            $log->update($updates);
        }

        return response()->json([
            'message' => 'Data ingested successfully',
            'date' => $date,
            'updated_fields' => array_keys($updates),
        ]);
    }
}
