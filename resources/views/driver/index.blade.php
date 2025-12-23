<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Loads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @forelse($loads as $load)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                 x-data="loadTracker({{ $load->id }})">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">Load #{{ $load->reference_no }}</h3>
                            <span class="badge {{ $load->status->color() }} mt-2">
                                {{ $load->status->label() }}
                            </span>
                        </div>
                        <a href="{{ route('loads.show', $load) }}" class="text-primary-600 hover:text-primary-900">
                            View Details
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500">Pickup</p>
                            <p class="font-medium">{{ $load->pickup_address }}</p>
                            @if($load->pickup_at)
                            <p class="text-sm text-gray-500">{{ $load->pickup_at->format('M d, Y h:i A') }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Delivery</p>
                            <p class="font-medium">{{ $load->delivery_address }}</p>
                            @if($load->delivery_at)
                            <p class="text-sm text-gray-500">{{ $load->delivery_at->format('M d, Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Tracking Controls -->
                    <div class="border-t pt-4 space-y-3">
                        <div class="flex gap-2">
                            <button @click="startTracking" 
                                    x-show="!isTracking"
                                    class="btn">
                                Start Tracking
                            </button>
                            <button @click="stopTracking" 
                                    x-show="isTracking"
                                    class="btn-danger">
                                Stop Tracking
                            </button>
                            <span x-show="isTracking" class="flex items-center text-green-600">
                                <svg class="animate-pulse h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <circle cx="10" cy="10" r="6"/>
                                </svg>
                                Tracking Active
                            </span>
                        </div>

                        <div x-show="error" class="text-red-600 text-sm" x-text="error"></div>
                        <div x-show="lastUpdate" class="text-gray-500 text-sm">
                            Last update: <span x-text="lastUpdate"></span>
                        </div>
                    </div>

                    <!-- Status Update -->
                    <div class="border-t pt-4 mt-4">
                        <h4 class="font-semibold mb-2">Update Load Status</h4>
                        <form method="POST" action="{{ route('loads.update-status', $load) }}" class="flex gap-2">
                            @csrf
                            <select name="status" class="input flex-1">
                                <option value="en_route" {{ $load->status->value == 'en_route' ? 'selected' : '' }}>En Route to Pickup</option>
                                <option value="arrived_pickup" {{ $load->status->value == 'arrived_pickup' ? 'selected' : '' }}>Arrived at Pickup</option>
                                <option value="loaded" {{ $load->status->value == 'loaded' ? 'selected' : '' }}>Loaded</option>
                                <option value="in_transit" {{ $load->status->value == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="delivered" {{ $load->status->value == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            <button type="submit" class="btn">Update</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    No active loads assigned to you.
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <script>
        function loadTracker(loadId) {
            return {
                isTracking: false,
                watchId: null,
                error: '',
                lastUpdate: '',
                intervalId: null,
                
                startTracking() {
                    if (!navigator.geolocation) {
                        this.error = 'Geolocation is not supported by your browser';
                        return;
                    }
                    
                    this.isTracking = true;
                    this.error = '';
                    
                    // Get initial position
                    this.updatePosition();
                    
                    // Update every 10 seconds
                    this.intervalId = setInterval(() => this.updatePosition(), 10000);
                },
                
                stopTracking() {
                    this.isTracking = false;
                    if (this.intervalId) {
                        clearInterval(this.intervalId);
                    }
                },
                
                updatePosition() {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const data = {
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude,
                                heading: position.coords.heading,
                                speed: position.coords.speed,
                                accuracy: position.coords.accuracy,
                                load_id: loadId
                            };
                            
                            fetch('/api/driver/location', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Authorization': 'Bearer ' + this.getApiToken()
                                },
                                body: JSON.stringify(data)
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    this.lastUpdate = new Date().toLocaleTimeString();
                                    this.error = '';
                                }
                            })
                            .catch(err => {
                                this.error = 'Failed to update location';
                                console.error(err);
                            });
                        },
                        (error) => {
                            this.error = 'Unable to retrieve your location';
                            console.error(error);
                        }
                    );
                },
                
                getApiToken() {
                    // In production, store token after login
                    return localStorage.getItem('api_token') || '';
                }
            }
        }
    </script>
</x-app-layout>
