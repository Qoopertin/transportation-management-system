@php
    $totalLoads = \App\Models\Load::count();
    $activeLoads = \App\Models\Load::active()->count();
    $totalDrivers = \App\Models\User::role('driver')->count();
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="text-sm text-gray-500 uppercase">Total Loads</div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalLoads }}</div>
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="text-sm text-gray-500 uppercase">Active Loads</div>
            <div class="text-3xl font-bold text-primary-600">{{ $activeLoads }}</div>
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="text-sm text-gray-500 uppercase">Total Drivers</div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalDrivers }}</div>
        </div>
    </div>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
        <div class="flex gap-4">
            <a href="{{ route('loads.create') }}" class="btn">Create New Load</a>
            <a href="{{ route('drivers.map') }}" class="btn-secondary">View Driver Map</a>
        </div>
    </div>
</div>
