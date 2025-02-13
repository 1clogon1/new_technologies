<?php

namespace App\Services;

use App\Models\AppTop;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class AppTopService
{
    public function getTopByDate(string $date)
    {
        try {
            $cacheKey = "app_top:{$date}";

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return response()->json(['error' => 'Invalid date format'], 400);
            }

            return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($date) {
                $data = AppTop::where('date', $date)
                    ->orderBy('position', 'asc')
                    ->get(['category', 'position']);

                if ($data->isEmpty()) {
                    return response()->json(['error' => 'No data found for this date'], 404);
                }

                return response()->json([
                    'status_code' => 200,
                    'message' => 'ok',
                    'data' => $data->pluck('position', 'category'),
                ]);
            });
        } catch (\Throwable $e) {
            Log::error("Error fetching top categories", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function saveInfoBD()
    {
        try {
            $applicationId = 1421444;
            $countryId = 1;
            $dateTo = now()->format('Y-m-d');
            $dateFrom = now()->subDays(30)->format('Y-m-d');

            $response = Http::get("https://api.apptica.com/package/top_history/{$applicationId}/{$countryId}?date_from={$dateFrom}&date_to={$dateTo}&B4NKGg=fVN5Q9KVOlOHDx9mOsKPAQsFBlEhBOwguLkNEDTZvKzJzT3l");

            if ($response->failed()) {
                Log::error("Failed to fetch data from API", [
                    'applicationId' => $applicationId,
                    'countryId' => $countryId,
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return response()->json(['error' => 'Failed to fetch data'], 500);
            }

            $data = $response->json('data');

            if (!$data) {
                Log::warning("No data received from API", [
                    'applicationId' => $applicationId,
                    'countryId' => $countryId,
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo,
                ]);
                return response()->json(['error' => 'No data received'], 400);
            }

            $dateMinPosition = [];

            foreach ($data as $category => $positions) {
                foreach ($positions as $dates) {
                    foreach ($dates as $date => $rank) {
                        // Проверяем на null и сохраняем null, если rank отсутствует
                        if (!isset($dateMinPosition[$date]) || ($rank !== null && $rank < $dateMinPosition[$date]['position'])) {
                            $dateMinPosition[$date] = [
                                'position' => $rank ?? null,
                                'category' => $category,
                                'date' => $date,
                            ];
                        }
                    }
                }
            }

            if (!empty($dateMinPosition)) {
                AppTop::upsert($dateMinPosition, ['category', 'date'], ['position']);
            }

            return response()->json(['success' => 'Data saved successfully']);
        } catch (Throwable $e) {
            Log::error("Exception in saveInfoBD", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
