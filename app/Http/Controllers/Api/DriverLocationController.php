<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDriverLocationRequest;
use App\Services\DriverLocationService;
use Illuminate\Http\JsonResponse;

class DriverLocationController extends Controller
{
    public function __construct(private DriverLocationService $driverLocationService)
    {
        $this->middleware(['auth:sanctum', 'role:driver', 'throttle:60,1']);
    }

    public function update(UpdateDriverLocationRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $locationData = $request->only(['latitude', 'longitude', 'heading', 'speed', 'accuracy']);
        
        $location = $this->driverLocationService->updateLocation($userId, $locationData);
        
        // If load_id is provided, also store breadcrumb
        if ($request->filled('load_id')) {
            $this->driverLocationService->recordBreadcrumb(
                $userId,
                $request->load_id,
                $request->latitude,
                $request->longitude
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => [
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'captured_at' => $location->captured_at->toIso8601String(),
            ],
        ]);
    }
}
