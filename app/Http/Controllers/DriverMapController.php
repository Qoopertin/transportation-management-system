<?php

namespace App\Http\Controllers;

use App\Services\DriverLocationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverMapController extends Controller
{
    public function __construct(private DriverLocationService $driverLocationService)
    {
        $this->middleware(['role:admin|dispatcher']);
    }

    public function index(Request $request): View
    {
        $locations = $this->driverLocationService->getActiveDriverLocations();
        
        $driversData = $locations->map(function ($location) {
            return [
                'id' => $location->user_id,
                'name' => $location->driver->name,
                'lat' => (float) $location->latitude,
                'lng' => (float) $location->longitude,
                'heading' => $location->heading,
                'speed' => $location->speed,
                'captured_at' => $location->captured_at->diffForHumans(),
            ];
        });
        
        return view('drivers.map', compact('driversData'));
    }
}
