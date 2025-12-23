<?php

namespace App\Services;

use App\Models\DriverBreadcrumb;
use App\Models\DriverLocation;
use App\Models\Load;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DriverLocationService
{
    private const CACHE_KEY_PREFIX = 'driver_location:';
    private const CACHE_TTL = 900; // 15 minutes

    public function updateLocation(int $userId, array $locationData): DriverLocation
    {
        $locationData['user_id'] = $userId;
        $locationData['captured_at'] = $locationData['captured_at'] ?? now();

        $location = DriverLocation::updateOrCreate(
            ['user_id' => $userId],
            $locationData
        );

        // Cache the latest location
        Cache::put(
            self::CACHE_KEY_PREFIX . $userId,
            $location,
            self::CACHE_TTL
        );

        \Log::info('Driver location updated', [
            'user_id' => $userId,
            'lat' => $location->latitude,
            'lng' => $location->longitude,
        ]);

        return $location;
    }

    public function recordBreadcrumb(int $userId, int $loadId, float $latitude, float $longitude): DriverBreadcrumb
    {
        return DriverBreadcrumb::create([
            'user_id' => $userId,
            'load_id' => $loadId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'captured_at' => now(),
        ]);
    }

    public function getActiveDriverLocations(): Collection
    {
        // Try to get from cache first
        $cachedLocations = $this->getCachedLocations();
        
        if ($cachedLocations->isNotEmpty()) {
            return $cachedLocations;
        }

        // Fallback to database
        return DriverLocation::recent(15)
            ->with('driver')
            ->get();
    }

    public function getDriverLocation(int $userId): ?DriverLocation
    {
        return Cache::remember(
            self::CACHE_KEY_PREFIX . $userId,
            self::CACHE_TTL,
            fn() => DriverLocation::where('user_id', $userId)->first()
        );
    }

    public function getLoadBreadcrumbs(Load $load): Collection
    {
        return $load->breadcrumbs()
            ->orderBy('captured_at')
            ->get()
            ->map(fn($breadcrumb) => [
                'lat' => (float) $breadcrumb->latitude,
                'lng' => (float) $breadcrumb->longitude,
                'captured_at' => $breadcrumb->captured_at->toIso8601String(),
            ]);
    }

    private function getCachedLocations(): Collection
    {
        // In a real implementation, you'd cache all active driver IDs and batch fetch
        // For simplicity, returning empty collection to use DB fallback
        return collect();
    }
}
