@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Patroli Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $result['totalPatroli'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-gray-500">
                        @if($result['totalPatroli'] > $result['totalPatroliYesterday'])
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span>{{ $result['totalPatroli'] - $result['totalPatroliYesterday'] }} lebih banyak dari kemarin</span>
                        @elseif($result['totalPatroli'] < $result['totalPatroliYesterday'])
                            <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                            <span>{{ $result['totalPatroliYesterday'] - $result['totalPatroli'] }} lebih sedikit dari kemarin</span>
                        @else
                            <i class="fas fa-equals text-gray-500 mr-1"></i>
                            <span>Sama dengan kemarin</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kejadian Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $result['totalKejadian'] }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-gray-500">
                        @if($result['totalKejadian'] > $result['totalKejadianYesterday'])
                            <i class="fas fa-arrow-up text-red-500 mr-1"></i>
                            <span>{{ $result['totalKejadian'] - $result['totalKejadianYesterday'] }} lebih banyak dari kemarin</span>
                        @elseif($result['totalKejadian'] < $result['totalKejadianYesterday'])
                            <i class="fas fa-arrow-down text-green-500 mr-1"></i>
                            <span>{{ $result['totalKejadianYesterday'] - $result['totalKejadian'] }} lebih sedikit dari kemarin</span>
                        @else
                            <i class="fas fa-equals text-gray-500 mr-1"></i>
                            <span>Sama dengan kemarin</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Patroli Aktif</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">3</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-walking text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-clock text-yellow-500 mr-1"></i>
                        <span>Dalam patroli</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Lokasi Patroli Terakhir</h2>
                </div>
                <div id="patroli-map" class="w-full h-[400px] rounded-lg"></div>
            </div>

            <!-- Recent Kejadian Section -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Kejadian Terbaru</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($result['kejadian'] as $kejadian)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $kejadian['nama_kejadian'] }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $kejadian['lokasi_kejadian'] }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                Baru
                            </span>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="far fa-clock mr-1"></i>
                            <span>{{ \Carbon\Carbon::parse($kejadian['tanggal'])->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-gray-200">
                    <a href="{{ route('admin.kejadian.kejadian') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua Kejadian
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #patroli-map {
        min-height: 400px;
        width: 100%;
        z-index: 0;
        height: 400px !important;
    }
    .leaflet-container {
        height: 100%;
        width: 100%;
    }
    
    /* Custom popup styles */
    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 12px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .custom-popup .leaflet-popup-content {
        margin: 0;
        width: auto !important;
    }
    
    .custom-popup .leaflet-popup-tip {
        background: white;
    }
    
    .custom-popup .leaflet-popup-close-button {
        color: #6B7280;
        font-size: 20px;
        padding: 8px;
        right: 4px;
        top: 4px;
    }
    
    .custom-popup .leaflet-popup-close-button:hover {
        color: #374151;
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('patroli-map').setView([-6.2088, 106.8456], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const colors = ['#FF5733', '#33FF57', '#3357FF', '#F3FF33', '#FF33F3'];

        fetch('/api/recent-patroli')
            .then(response => response.json())
            .then(data => {
                console.log('Received patrol data:', data);
                
                if (data.length > 0) {
                    const bounds = L.latLngBounds([]);
                    let markersAdded = 0;
                    
                    data.forEach((patrol, index) => {
                        console.log(`Processing patrol ${index + 1}:`, patrol);
                        
                        if (!patrol.latitude || !patrol.longitude || 
                            patrol.latitude === 0 || patrol.longitude === 0) {
                            console.log(`Skipping patrol ${index + 1} due to invalid coordinates`);
                            return;
                        }

                        const color = colors[index % colors.length];
                        
                        const marker = L.marker([patrol.latitude, patrol.longitude], {
                            icon: L.divIcon({
                                className: 'custom-marker',
                                html: `<div class="bg-white rounded-full p-2 shadow-lg">
                                        <i class="fas fa-map-marker-alt" style="color: ${color}"></i>
                                      </div>`,
                                iconSize: [30, 30],
                                iconAnchor: [15, 30]
                            })
                        }).addTo(map);
                        markersAdded++;

                        marker.bindPopup(`
                            <div class="p-4 max-w-xs">
                                <div class="flex items-center gap-3 mb-3 pb-3 border-b border-gray-200">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-map-marker-alt text-blue-600 text-lg"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900 text-lg">${patrol.lokasi_nama}</h3>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user text-gray-500 w-5"></i>
                                        <span class="text-gray-700">${patrol.satpam_nama}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar text-gray-500 w-5"></i>
                                        <span class="text-gray-700">${patrol.tanggal}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-clock text-gray-500 w-5"></i>
                                        <span class="text-gray-700">${patrol.jam_mulai}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-info-circle text-gray-500 w-5"></i>
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            ${patrol.status === 'Selesai' ? 'bg-green-100 text-green-800' : 
                                              (patrol.status === 'Terlambat' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')}">
                                            ${patrol.status}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <a href="/admin/patroli/${patrol.id}/detail" 
                                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                        <i class="fas fa-external-link-alt"></i>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        `, {
                            maxWidth: 300,
                            className: 'custom-popup'
                        });

                        bounds.extend([patrol.latitude, patrol.longitude]);

                        if (patrol.checkpoints && patrol.checkpoints.length > 0) {
                            const points = patrol.checkpoints.map(cp => [cp.latitude, cp.longitude]);
                            L.polyline(points, {
                                color: color,
                                weight: 3,
                                opacity: 0.7
                            }).addTo(map);
                        }
                    });

                    console.log(`Added ${markersAdded} markers to the map`);

                    if (markersAdded > 0) {
                        map.fitBounds(bounds);
                    }
                } else {
                    console.log('No patrol data received');
                }
            })
            .catch(error => {
                console.error('Error fetching patrol data:', error);
            });
    });
</script>
@endsection



