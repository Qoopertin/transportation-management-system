<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Load #{{ $load->reference_no }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Load Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Load Details</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <span class="ml-2 badge {{ $load->status->color() }}">
                                        {{ $load->status->label() }}
                                    </span>
                                </div>
                                
                                <div>
                                    <span class="text-sm text-gray-500">Pickup Address:</span>
                                    <p class="mt-1">{{ $load->pickup_address }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm text-gray-500">Delivery Address:</span>
                                    <p class="mt-1">{{ $load->delivery_address }}</p>
                                </div>
                                
                                @if($load->pickup_at)
                                <div>
                                    <span class="text-sm text-gray-500">Pickup Time:</span>
                                    <p class="mt-1">{{ $load->pickup_at->format('M d, Y h:i A') }}</p>
                                </div>
                                @endif
                                
                                @if($load->delivery_at)
                                <div>
                                    <span class="text-sm text-gray-500">Delivery Time:</span>
                                    <p class="mt-1">{{ $load->delivery_at->format('M d, Y h:i A') }}</p>
                                </div>
                                @endif
                                
                                @if($load->notes)
                                <div>
                                    <span class="text-sm text-gray-500">Notes:</span>
                                    <p class="mt-1">{{ $load->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Route Map -->
                    @if($breadcrumbs->isNotEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Route History</h3>
                            <div id="route-map" style="height: 400px;" class="rounded-lg"></div>
                        </div>
                    </div>
                    @endif

                    <!-- Documents -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Documents</h3>
                            
                            @if($load->documents->isNotEmpty())
                            <div class="space-y-2">
                                @foreach($load->documents as $doc)
                                <div class="flex items-center justify-between border-b pb-2">
                                    <div>
                                        <p class="font-medium">{{ $doc->original_name }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $doc->type->label() }} · {{ $doc->size_in_mb }} MB · 
                                            Uploaded {{ $doc->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <a href="{{ $doc->url }}" target="_blank" class="text-primary-600 hover:text-primary-900">View</a>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-gray-500">No documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Actions -->
                <div class="space-y-6">
                    <!-- Driver Assignment -->
                    @can('update loads')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Driver Assignment</h3>
                            <form method="POST" action="{{ route('loads.assign-driver', $load) }}">
                                @csrf
                                <select name="driver_id" class="input mb-3">
                                    <option value="">-- Unassign --</option>
                                    @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ $load->assigned_driver_id == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn w-full">Update Driver</button>
                            </form>
                        </div>
                    </div>

                    <!-- Status Update -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Update Status</h3>
                            <form method="POST" action="{{ route('loads.update-status', $load) }}">
                                @csrf
                                <select name="status" class="input mb-3">
                                    @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ $load->status == $status ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn w-full">Update Status</button>
                            </form>
                        </div>
                    </div>
                    @endcan

                    <!-- Current Driver Info -->
                    @if($load->driver)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Assigned Driver</h3>
                            <p class="text-gray-900">{{ $load->driver->name }}</p>
                            <p class="text-sm text-gray-500">{{ $load->driver->email }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($breadcrumbs->isNotEmpty())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const breadcrumbs = @json($breadcrumbs);
            
            // Initialize map
            const map = L.map('route-map').setView([breadcrumbs[0].lat, breadcrumbs[0].lng], 10);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Draw route polyline
            const latlngs = breadcrumbs.map(b => [b.lat, b.lng]);
            const polyline = L.polyline(latlngs, {color: '#3b82f6', weight: 4}).addTo(map);
            
            // Add start marker
            L.marker([breadcrumbs[0].lat, breadcrumbs[0].lng])
                .bindPopup('Start')
                .addTo(map);
            
            // Add end marker
            const lastPoint = breadcrumbs[breadcrumbs.length - 1];
            L.marker([lastPoint.lat, lastPoint.lng])
                .bindPopup('Latest Position')
                .addTo(map);
            
            // Fit map to route
            map.fitBounds(polyline.getBounds());
        });
    </script>
    @endpush
    @endif
</x-app-layout>
