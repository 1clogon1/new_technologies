<?php

namespace App\Console\Commands;

use App\Services\AppTopService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\YourModel; // Замени на свою модель
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FetchAppticaData extends Command
{
    protected $signature = 'fetch:appdata';
    protected $description = 'Fetch app ranking data from Apptica and save it to the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $service = new AppTopService();
            $service->saveInfoBD();
            Log::info('apptica:fetch executed successfully.');
        } catch (\Throwable $e) {
            Log::error('Error in apptica:fetch command', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
