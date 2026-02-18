<?php

namespace App\Services;

use Carbon\Carbon;
use ICal\ICal;
use Illuminate\Support\Facades\Cache;

class GoogleCalendarService
{
    protected const ICAL_URL = 'https://calendar.google.com/calendar/ical/sofb02k7ebbqsl03rosohq227o%40group.calendar.google.com/public/basic.ics';

    protected const CACHE_KEY = 'feed_deliveries';

    protected const CACHE_TTL = 3600;

    public function getUpcomingFeedDeliveries(int $years = 2, int $limit = 10): array
    {
        $events = $this->fetchAllEvents($years);

        return collect($events)
            ->filter(fn ($event) => ($event->summary ?? '') === 'MeinHof')
            ->map(function ($event) {
                $start = $event->dtstart_tz instanceof \DateTimeInterface
                    ? $event->dtstart_tz
                    : new \DateTime($event->dtstart);

                return [
                    'date' => Carbon::instance($start)->format('Y-m-d'),
                    'display_date' => Carbon::instance($start)->format('d.m.Y'),
                    'title' => $event->summary ?? 'Futter',
                ];
            })
            ->sortBy('date')
            ->filter(fn ($event) => $event['date'] >= Carbon::today()->format('Y-m-d'))
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function getAllDeliveries(int $years = 5): array
    {
        $events = $this->fetchAllEvents($years);

        return collect($events)
            ->filter(fn ($event) => ($event->summary ?? '') === 'MeinHof')
            ->map(function ($event) {
                $start = $event->dtstart_tz instanceof \DateTimeInterface
                    ? $event->dtstart_tz
                    : new \DateTime($event->dtstart);

                return [
                    'date' => Carbon::instance($start)->format('Y-m-d'),
                    'display_date' => Carbon::instance($start)->format('d.m.Y'),
                    'title' => $event->summary ?? 'Futter',
                ];
            })
            ->sortBy('date')
            ->values()
            ->toArray();
    }

    public function calculateInterval(): int
    {
        $deliveries = $this->getUpcomingFeedDeliveries(5, 100);

        if (count($deliveries) < 2) {
            return 0;
        }

        $intervals = [];
        for ($i = 1; $i < count($deliveries); $i++) {
            $prev = Carbon::parse($deliveries[$i - 1]['date']);
            $curr = Carbon::parse($deliveries[$i]['date']);
            $intervals[] = $prev->diffInDays($curr);
        }

        return (int) round(array_sum($intervals) / count($intervals));
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected function fetchAllEvents(int $years): array
    {
        $cacheKey = self::CACHE_KEY.'_'.$years;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($years) {
            $allEvents = [];

            for ($i = 0; $i < $years; $i++) {
                $year = Carbon::now()->year + $i;
                $events = $this->fetchEvents($year);
                $allEvents = array_merge($allEvents, $events);
            }

            return $allEvents;
        });
    }

    protected function fetchEvents(int $year): array
    {
        try {
            $ical = new ICal(self::ICAL_URL, [
                'defaultTimeZone' => 'Europe/Berlin',
            ]);

            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();

            return $ical->eventsFromRange($startDate, $endDate);
        } catch (\Exception $e) {
            report($e);

            return [];
        }
    }
}
