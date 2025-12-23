<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Driver Map') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div id="driver-map" style="height: 600px;" class="rounded-lg"></div>
                </div>
            </div>
            
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Active Drivers</h3>
                    <div id="driver-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const drivers = @json($driversData);
            
            // Initialize map (centered on US)
            const map = L.map('driver-map').setView([39.8283, -98.5795], 4);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Add driver markers
            const markers = [];
            drivers.forEach(driver => {
                const marker = L.marker([driver.lat, driver.lng])
                    .bindPopup(`
                        <strong>${driver.name}</strong><br>
                        Speed: ${driver.speed || 'N/A'} km/h<br>
                        Updated: ${driver.captured_at}
                    `)
                    .addTo(map);
                markers.push(marker);
                
                // Add to driver list
                const driverCard = document.createElement('div');
                driverCard.className = 'border rounded-lg p-4';
                driverCard.innerHTML = `
                    <h4 class="font-semibold">${driver.name}</h4>
                    <p class="text-sm text-gray-500">Updated: ${driver.captured_at}</p>
                `;
                document.getElementById('driver-list').appendChild(driverCard);
            });
            
            // Fit map to show all markers
            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        });
    </script>
</x-app-layout>
