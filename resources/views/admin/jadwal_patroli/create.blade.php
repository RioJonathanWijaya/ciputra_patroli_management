@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-3xl p-10 space-y-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-gray-800 mb-6 animate-slide-in">Tambah Jadwal Patroli</h1>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl animate-fade-in">
        <strong class="block mb-2">Terjadi kesalahan:</strong>
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.jadwal_patroli.store') }}" method="POST" onsubmit="return validateForm()" class="space-y-6">
        @csrf

        <div class="transition duration-300 ease-in-out transform hover:scale-[1.01]">
            <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
            <select id="lokasi" name="lokasi" class="select2 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3">
                <option value="">Pilih Lokasi</option>
                @foreach($lokasiData as $id => $lokasi)
                <option value="{{ $id }}">{{ $lokasi['nama_lokasi'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="transition duration-300 ease-in-out transform hover:scale-[1.01]">
            <label for="satpam_shift_pagi" class="block text-sm font-medium text-gray-700 mb-2">Satpam Shift Pagi</label>
            <select id="satpam_shift_pagi" name="satpam_shift_pagi" class="select2 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3">
                <option value="">Pilih Satpam</option>
                @foreach($satpamPagi as $id => $satpam)
                <option value="{{ $id }}">{{ $satpam['nip'] }} - {{ $satpam['nama'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="transition duration-300 ease-in-out transform hover:scale-[1.01]">
            <label for="satpam_shift_malam" class="block text-sm font-medium text-gray-700 mb-2">Satpam Shift Malam</label>
            <select id="satpam_shift_malam" name="satpam_shift_malam" class="select2 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3">
                <option value="">Pilih Satpam</option>
                @foreach($satpamMalam as $id => $satpam)
                <option value="{{ $id }}">{{ $satpam['nip'] }} - {{ $satpam['nama'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="transition duration-300 ease-in-out transform hover:scale-[1.01]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Titik Patroli</label>
            <div id="map" class="h-[300px] w-full rounded-2xl border border-gray-300 shadow-inner animate-fade-in"></div>
            <button type="button" id="addPoint" class="mt-3 inline-flex items-center bg-blue-600 hover:bg-blue-700 transition-all duration-300 transition-opacity text-white text-sm px-5 py-2 rounded-xl shadow-md">
                <svg id="spinnerIcon" class="hidden w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 000 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
                </svg>
                <span id="addPointText">+ Tambah Titik</span>
            </button>
            <input type="hidden" id="titik_patrol" name="titik_patrol">

            <div id="previewPoints" class="text-sm space-y-2 mt-4">
                <strong class="block text-gray-700">Titik yang Ditambahkan:</strong>
                <ul class="list-decimal list-inside space-y-1" id="pointsList"></ul>
            </div>
        </div>

        <div class="transition duration-300 ease-in-out transform hover:scale-[1.01]">
            <label for="interval_patroli" class="block text-sm font-medium text-gray-700 mb-2">Interval Patroli (Setiap ... Jam)</label>
            <input type="number" name="interval_patroli" id="interval_patroli" min="1" class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Contoh: 2">
            <small class="text-gray-500">Masukkan berapa jam sekali satpam melakukan patroli (misal: 2 berarti setiap 2 jam sekali).</small>
        </div>

        <div id="previewInterval" class="mt-6 hidden">
            <strong class="block text-gray-700 mb-3 text-base">Preview Jam Patroli:</strong>
            <div id="intervalList" class="flex flex-wrap gap-3"></div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 transition-all duration-300 text-white font-bold py-3 rounded-2xl shadow-xl text-lg animate-pop-in">
                Simpan Jadwal Patroli
            </button>
        </div>
    </form>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-6 right-6 z-50 space-y-3"></div>

<!-- Select2 & Leaflet -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
    .btn-aktif {
        background-color: #f59e0b !important;
        color: white !important;
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes fade-in {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }

    @keyframes slide-in {
        0% {
            opacity: 0;
            transform: translateX(-20px);
        }

        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-slide-in {
        animation: slide-in 0.6s ease-in-out;
    }

    @keyframes pop-in {
        0% {
            transform: scale(0.9);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-pop-in {
        animation: pop-in 0.5s ease-in-out;

        .leaflet-polyline-glow {
            stroke: #3b82f6;
            stroke-width: 6;
            stroke-opacity: 0.5;
            filter: url(#glowFilter);
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }

        @keyframes pulse-glow {
            0% {
                stroke-opacity: 0.3;
            }

            100% {
                stroke-opacity: 0.7;
            }
        }

        svg defs {
            position: absolute;
            z-index: -1;
        }

    }
</style>

<div id="orsApiKey" data-key="{{ env('ORS_API_KEY') }}"></div>
<script src="{{ asset('js/jadwal_patrol.js') }}"></script>


@endsection