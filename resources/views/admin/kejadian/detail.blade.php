@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header with gradient background -->
    <div class="bg-gradient-to-r from-[#1C3A6B] to-[#2a4f8a] text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">{{ $kejadianData['nama_kejadian'] ?? 'Detail Kejadian' }}</h1>
                    <div class="flex items-center gap-4 mt-2 text-gray-200">
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $kejadianData['tanggal_kejadian'] ? \Carbon\Carbon::parse($kejadianData['tanggal_kejadian'])->format('d M Y') : '-' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $kejadianData['lokasi_kejadian'] ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.kejadian.kejadian') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <button onclick="window.print()" class="px-4 py-2 bg-white text-[#1C3A6B] rounded-lg hover:bg-gray-100 transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                        </svg>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Left Column - Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Status Kejadian</h2>
                        <span class="px-4 py-1 rounded-full text-sm font-medium {{ 
                            $kejadianData['status'] === 'Selesai' ? 'bg-green-100 text-green-800' : 
                            ($kejadianData['status'] === 'Proses' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-blue-100 text-blue-800') 
                        }}">
                            {{ $kejadianData['status'] ?? '-' }}
                        </span>
                    </div>
                    <p class="text-gray-600">{{ $kejadianData['keterangan'] ?? '-' }}</p>
                </div>

                @if(isset($kejadianData['foto_bukti_kejadian']) && count($kejadianData['foto_bukti_kejadian']) > 0)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Dokumentasi Kejadian</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($kejadianData['foto_bukti_kejadian'] as $foto)
                        <div class="relative aspect-[4/3] rounded-xl overflow-hidden group">
                            <img src="{{ $foto }}" alt="Foto Bukti" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <a href="{{ $foto }}" target="_blank" class="text-white text-sm hover:underline">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Additional Information -->
            <div class="space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Kejadian</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-500">Tipe Kejadian</label>
                            <p class="font-medium text-gray-800">{{ $kejadianData['tipe_kejadian'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Waktu Laporan</label>
                            <p class="font-medium text-gray-800">{{ $kejadianData['waktu_laporan'] ? \Carbon\Carbon::parse($kejadianData['waktu_laporan'])->format('d M Y H:i') : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Petugas</label>
                            <p class="font-medium text-gray-800">{{ $kejadianData['satpam_nama'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Width Tindakan Section -->
        <div class="space-y-6">
            <!-- Tindakan Form Section -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Tindakan Baru</h2>
                <form action="{{ route('admin.kejadian.saveTindakan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kejadian_id" value="{{ $kejadianData['id'] }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tindakan</label>
                            <textarea 
                                id="tindakan" 
                                name="tindakan" 
                                rows="3" 
                                class="w-full rounded-lg border border-solid border-black focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3"
                                required
                                placeholder="Tuliskan tindakan yang akan diambil..."
                            ></textarea>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Update Status Kejadian</label>
                            <select 
                                id="status" 
                                name="status" 
                                class="w-full rounded-lg border border-solid border-black focus:border-blue-500 focus:ring-1 focus:ring-blue-500 p-3"
                                required
                            >
                                <option value="">Pilih Status</option>
                                <option value=0 {{ $kejadianData['status'] === 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value=1 {{ $kejadianData['status'] === 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option value=2 {{ $kejadianData['status'] === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1C3A6B] hover:bg-[#2a4f8a] text-white font-medium rounded-lg transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Simpan Tindakan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Timeline of Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Tindakan</h2>
                <div class="space-y-4">
                    @if(isset($kejadianData['tindakan']) && count($kejadianData['tindakan']) > 0)
                        @foreach($kejadianData['tindakan'] as $tindakan)
                            <div class="relative pl-8 pb-8 last:pb-0">
                                <div class="absolute left-0 top-2 w-4 h-4 bg-[#1C3A6B] rounded-full border-4 border-white shadow"></div>
                                @if(!$loop->last)
                                    <div class="absolute left-2 top-6 bottom-0 w-[1px] bg-gray-200"></div>
                                @endif
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($tindakan['waktu_tindakan'])->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700">{{ $tindakan['tindakan'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500">Belum ada tindakan yang diambil</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none;
        }
        body {
            background-color: white;
        }
    }

    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 20px;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #0d6efd;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #0d6efd;
    }

    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: 7px;
        top: 15px;
        height: calc(100% + 5px);
        width: 2px;
        background: #e9ecef;
    }

    .timeline-heading {
        margin-bottom: 10px;
    }

    .timeline-body {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary {
        padding: 0.5rem 1.5rem;
        font-weight: 500;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
    }
</style>

<script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
@endsection