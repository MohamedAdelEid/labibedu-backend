<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Infrastructure\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DatabaseController extends Controller
{
    public function refreshAndSeed(): JsonResponse
    {
        // Only allow in local/development environment
        // if (!app()->environment(['local', 'development'])) {
        //     return ApiResponse::error(
        //         'This endpoint is only available in development environment.',
        //         null,
        //         403
        //     );
        // }

        try {
            Log::info('Starting database refresh and seed...');

            // Run migrate:fresh
            Artisan::call('migrate:fresh', ['--force' => true]);
            $migrateOutput = Artisan::output();
            Log::info('Migration output: ' . $migrateOutput);

            // Run db:seed
            Artisan::call('db:seed', ['--force' => true]);
            $seedOutput = Artisan::output();
            Log::info('Seed output: ' . $seedOutput);

            return ApiResponse::success([
                'migration_output' => trim($migrateOutput),
                'seed_output' => trim($seedOutput),
            ], 'Database refreshed and seeded successfully.');
        } catch (\Exception $e) {
            Log::error('Database refresh error: ' . $e->getMessage());

            return ApiResponse::error(
                'An error occurred while refreshing the database: ' . $e->getMessage(),
                null,
                500
            );
        }
    }
}

