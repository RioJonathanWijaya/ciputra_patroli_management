@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Peta Patroli Terakhir</h1>
        </div>

        <div id="map" class="w-full h-[600px] rounded-lg"></div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        const map = L.map('map').setView([-6.2088, 106.8456], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Colors for different patroli
        const colors = [
            '#FF5733', // Red
            '#33FF57', // Green
            '#3357FF', // Blue
            '#F333FF', // Purple
            '#FF33F3'  // Pink
        ];

        // Fetch recent patroli data
        fetch('/api/recent-patroli')
            .then(response => response.json())
            .then(data => {
                data.forEach((patroli, index) => {
                    const color = colors[index % colors.length];
                    
                    // Create marker for each patroli
                    const marker = L.marker([patroli.latitude, patroli.longitude], {
                        icon: L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div style="background-color: ${color}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white;"></div>`,
                            iconSize: [12, 12],
                            iconAnchor: [6, 6]
                        })
                    }).addTo(map);

                    // Add popup with patroli information
                    marker.bindPopup(`
                        <div class="p-2">
                            <h3 class="font-bold">${patroli.nama_lokasi}</h3>
                            <p class="text-sm">Satpam: ${patroli.nama_satpam}</p>
                            <p class="text-sm">Tanggal: ${patroli.tanggal}</p>
                            <p class="text-sm">Jam Mulai: ${patroli.jam_mulai}</p>
                            <p class="text-sm">Status: ${patroli.status}</p>
                        </div>
                    `);

                    // If there are checkpoints, create a polyline
                    if (patroli.checkpoints && patroli.checkpoints.length > 0) {
                        const points = patroli.checkpoints.map(cp => [cp.latitude, cp.longitude]);
                        L.polyline(points, {
                            color: color,
                            weight: 3,
                            opacity: 0.7
                        }).addTo(map);
                    }
                });

                // Adjust map view to show all markers
                if (data.length > 0) {
                    const bounds = data.map(p => [p.latitude, p.longitude]);
                    map.fitBounds(bounds);
                }
            })
            .catch(error => console.error('Error fetching patroli data:', error));
    });
</script>
@endpush
@endsection 