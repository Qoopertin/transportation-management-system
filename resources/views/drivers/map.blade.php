<x-app-layout>
    <x-slot name="header">
        Driver Map
    </x-slot>

    <!-- Full Height Map -->
    <div class="h-full flex flex-col">
        <div class="flex-1" id="driver-map"></div>
        
        <!-- Driver List Sidebar (overlay) -->
        <div x-data="{ open: false }" class="absolute top-20 right-6 w-80 bg-gray-800 rounded-lg shadow-xl border border-gray-700 max-h-[calc(100vh-120px)] overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold">Active Drivers</h3>
                <span class="text-sm text-gray-400" id="driver-count">0 drivers</span>
            </div>
            <div id="driver-list" class="flex-1 overflow-y-auto p-4 space-y-2">
                <!-- Populated by JavaScript -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const drivers = @json($driversData);
            
            // Initialize map (full screen)
            const map = L.map('driver-map', {
                zoomControl: true
            }).setView([39.8283, -98.5795], 4);
            
            // Dark theme map tiles
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors, © CARTO'
            }).addTo(map);
            
            // Custom marker icon
            const driverIcon = L.divIcon({
                html: `<div class="w-8 h-8 bg-blue-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                </div>`,
                className: '',
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });
            
            // Add driver markers
            const markers = [];
            const driverListEl = document.getElementById('driver-list');
            
            drivers.forEach((driver, index) => {
                const marker = L.marker([driver.lat, driver.lng], { icon: driverIcon })
                    .bindPopup(`
                        <div class="p-2">
                            <strong class="text-lg">${driver.name}</strong><br>
                            <span class="text-sm text-gray-600">Speed: ${driver.speed || 'N/A'} km/h</span><br>
                            <span class="text-xs text-gray-500">Updated: ${driver.captured_at}</span>
                        </div>
                    `)
                    .addTo(map);
                markers.push(marker);
                
                // Add to driver list
                const driverCard = document.createElement('div');
                driverCard.className = 'p-3 bg-gray-900 rounded-lg hover:bg-gray-700 cursor-pointer transition';
                driverCard.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium">${driver.name}</h4>
                            <p class="text-xs text-gray-400">Updated: ${driver.captured_at}</p>
                        </div>
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    </div>
                `;
                driverCard.addEventListener('click', () => {
                    map.setView([driver.lat, driver.lng], 15);
                    marker.openPopup();
                });
                driverListEl.appendChild(driverCard);
            });
            
            // Update driver count
            document.getElementById('driver-count').textContent = `${drivers.length} driver${drivers.length !== 1 ? 's' : ''}`;
            
            // Fit map to show all markers
            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        });
    </script>

    <style>
        #driver-map {
            background-color: #1f2937;
        }
        .leaflet-popup-content-wrapper {
            background-color: white;
            border-radius: 8px;
        }
    </style>
</x-app-layout>
