@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                <a href="{{ route('admin.patroli.patroli') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Patroli</h1>
            </div>
            <div class="flex space-x-4">
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $patroli['status'] === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $patroli['status'] }}
                </span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Patrol Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Patrol Information Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Patroli</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p class="font-medium text-gray-900">{{ $patroli['nama_lokasi'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="fas fa-user-shield text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Satpam</p>
                                <p class="font-medium text-gray-900">{{ $patroli['nama_satpam'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="fas fa-calendar-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal</p>
                                <p class="font-medium text-gray-900">{{ $patroli['tanggal'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Waktu</p>
                                <p class="font-medium text-gray-900">{{ $patroli['waktu_mulai'] }} - {{ $patroli['waktu_selesai'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkpoints Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Checkpoints</h2>
                    <div class="space-y-4">
                        @foreach($patroli['checkpoints'] as $checkpoint)
                        <div class="flex flex-col space-y-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-map-pin text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $checkpoint['nama'] }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium {{ $checkpoint['status'] === 'On Time' ? 'text-green-600' : 'text-yellow-600' }}">
                                                {{ $checkpoint['status'] }}
                                            </span>
                                            • {{ Carbon\Carbon::parse($checkpoint['timestamp'])->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $checkpoint['distance_status'] === 'Far' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $checkpoint['distance_status'] }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2 text-sm">
                                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                                        <span class="text-gray-700">Target Location:</span>
                                    </div>
                                    <div class="pl-6 text-sm text-gray-600">
                                        {{ $checkpoint['latitude'] }}, {{ $checkpoint['longitude'] }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2 text-sm">
                                        <i class="fas fa-location-arrow text-gray-500"></i>
                                        <span class="text-gray-700">Current Location:</span>
                                    </div>
                                    <div class="pl-6 text-sm text-gray-600">
                                        {{ $checkpoint['current_latitude'] }}, {{ $checkpoint['current_longitude'] }}
                                    </div>
                                </div>
                            </div>

                            @if($checkpoint['keterangan'])
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2 text-sm">
                                    <i class="fas fa-comment text-gray-500"></i>
                                    <span class="text-gray-700">Notes:</span>
                                </div>
                                <div class="pl-6 text-sm text-gray-600">
                                    {{ $checkpoint['keterangan'] }}
                                </div>
                            </div>
                            @endif

                            @if($checkpoint['image_path'])
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2 text-sm">
                                    <i class="fas fa-camera text-gray-500"></i>
                                    <span class="text-gray-700">Photo:</span>
                                </div>
                                <div class="pl-6">
                                    <img src="{{ $checkpoint['image_path'] }}" alt="Checkpoint photo" 
                                        class="w-full max-w-xs rounded-lg shadow-sm">
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column - Additional Info -->
            <div class="space-y-6">
                <!-- Notes Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Catatan</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $patroli['catatan'] ?? 'Tidak ada catatan' }}</p>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi</h2>
                    <div class="space-y-3">
                        <a href="{{ route('admin.patroli.patroli') }}"
                            class="w-full flex items-center justify-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                        <form action="{{ route('admin.patroli.destroy', $patroli['id']) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-trash"></i>
                                <span>Hapus Patroli</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm justify-center items-center transition-all duration-300 ease-out">
    <div class="animate-fadeInUp bg-white p-6 rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-all duration-300 ease-out">
        <h2 class="text-xl font-bold text-red-600 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i> Konfirmasi Hapus
        </h2>
        <p class="text-gray-700 mb-5">Apakah Anda yakin ingin menghapus data patroli ini? Tindakan ini tidak bisa dibatalkan.</p>
        <form id="deleteForm" method="POST" class="flex justify-end gap-4">
            @csrf
            @method('DELETE')
            <button type="button" onclick="hideDeleteModal()"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold transition">
                Batal
            </button>
            <button type="submit"
                class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
    function showDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

</script>
@endsection 