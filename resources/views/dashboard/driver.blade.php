@php
    $myLoads = \App\Models\Load::forDriver(auth()->id())->active()->count();
    $completedLoads = \App\Models\Load::forDriver(auth()->id())->where('status', \App\Enums\LoadStatus::DELIVERED)->count();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="text-sm text-gray-500 uppercase">Active Loads</div>
            <div class="text-3xl font-bold text-primary-600">{{ $myLoads }}</div>
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="text-sm text-gray-500 uppercase">Completed Loads</div>
            <div class="text-3xl font-bold text-green-600">{{ $completedLoads }}</div>
        </div>
    </div>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
        <div class="flex gap-4">
            <a href="{{ route('driver.index') }}" class="btn">View My Loads</a>
        </div>
    </div>
</div>
