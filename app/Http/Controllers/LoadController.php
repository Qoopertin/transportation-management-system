<?php

namespace App\Http\Controllers;

use App\Enums\LoadStatus;
use App\Http\Requests\StoreLoadRequest;
use App\Http\Requests\UpdateLoadRequest;
use App\Models\Load;
use App\Models\User;
use App\Services\DriverLocationService;
use App\Services\LoadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoadController extends Controller
{
    public function __construct(
        private LoadService $loadService,
        private DriverLocationService $driverLocationService
    ) {
        $this->middleware('permission:view loads')->only(['index', 'show']);
        $this->middleware('permission:create loads')->only(['create', 'store']);
        $this->middleware('permission:update loads')->only(['edit', 'update', 'assignDriver', 'updateStatus']);
    }

    public function index(): View
    {
        $loads = $this->loadService->getPaginatedLoads();
        
        return view('loads.index', compact('loads'));
    }

    public function create(): View
    {
        $drivers = User::role('driver')->get();
        
        return view('loads.create', compact('drivers'));
    }

    public function store(StoreLoadRequest $request): RedirectResponse
    {
        $load = $this->loadService->createLoad($request->validated());
        
        return redirect()
            ->route('loads.show', $load)
            ->with('success', 'Load created successfully.');
    }

    public function show(Load $load): View
    {
        $load = $this->loadService->getLoadWithDetails($load->id);
        $drivers = User::role('driver')->get();
        $breadcrumbs = $this->driverLocationService->getLoadBreadcrumbs($load);
        $statuses = LoadStatus::cases();
        
        return view('loads.show', compact('load', 'drivers', 'breadcrumbs', 'statuses'));
    }

    public function edit(Load $load): View
    {
        $drivers = User::role('driver')->get();
        
        return view('loads.edit', compact('load', 'drivers'));
    }

    public function update(UpdateLoadRequest $request, Load $load): RedirectResponse
    {
        $this->loadService->updateLoad($load, $request->validated());
        
        return redirect()
            ->route('loads.show', $load)
            ->with('success', 'Load updated successfully.');
    }

    public function assignDriver(Request $request, Load $load): RedirectResponse
    {
        $request->validate([
            'driver_id' => 'nullable|exists:users,id',
        ]);
        
        $this->loadService->assignDriver($load, $request->driver_id);
        
        return back()->with('success', 'Driver assigned successfully.');
    }

    public function updateStatus(Request $request, Load $load): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_column(LoadStatus::cases(), 'value')),
        ]);
        
        $this->loadService->updateStatus($load, LoadStatus::from($request->status));
        
        return back()->with('success', 'Load status updated successfully.');
    }
}
