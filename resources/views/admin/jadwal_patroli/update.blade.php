@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-3xl p-10 space-y-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-gray-800 mb-6 animate-slide-in">Edit Jadwal Patroli</h1>

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

    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            toastr.success("{{ session('success') }}");
        });
    </script>
    @endif

    <form action="{{ route('admin.jadwal_patroli.update', $id) }}" method="POST" onsubmit="return validateForm()" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
            <select id="lokasi" name="lokasi" class="select2 w-full rounded-xl border border-gray-300 p-3" required>
                <option value="">Pilih Lokasi</option>
                @foreach($lokasiData as $key => $lokasi)
                <option value="{{ $key }}" {{ (isset($jadwal['lokasi']) && $jadwal['lokasi'] == $key) ? 'selected' : '' }}>{{ $lokasi['nama_lokasi'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="satpam_shift_pagi" class="block text-sm font-medium text-gray-700 mb-2">Satpam Shift Pagi</label>
            <select id="satpam_shift_pagi" name="satpam_shift_pagi" class="select2 w-full rounded-xl border border-gray-300 p-3" required>
                <option value="">Pilih Satpam</option>
                @foreach($satpamPagi as $key => $satpam)
                <option value="{{ $key }}" {{ (isset($jadwal['satpam_shift_pagi']) && $jadwal['satpam_shift_pagi'] == $key) ? 'selected' : '' }}>{{ $satpam['nip'] }} - {{ $satpam['nama'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="satpam_shift_malam" class="block text-sm font-medium text-gray-700 mb-2">Satpam Shift Malam</label>
            <select id="satpam_shift_malam" name="satpam_shift_malam" class="select2 w-full rounded-xl border border-gray-300 p-3" required>
                <option value="">Pilih Satpam</option>
                @foreach($satpamMalam as $key => $satpam)
                <option value="{{ $key }}" {{ (isset($jadwal['satpam_shift_malam']) && $jadwal['satpam_shift_malam'] == $key) ? 'selected' : '' }}>{{ $satpam['nip'] }} - {{ $satpam['nama'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Titik Patroli</label>
            <div id="map" class="h-[300px] w-full rounded-2xl border border-gray-300 shadow-inner animate-fade-in"></div>
            <input type="hidden" id="titik_patrol" name="titik_patrol" value='{{ json_encode($jadwal['titik_patrol'] ?? []) }}'>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 transition-all duration-300 text-white font-bold py-3 rounded-2xl shadow-xl text-lg animate-pop-in">
                Update Jadwal Patroli
            </button>
        </div>
    </form>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.select2').select2({
            placeholder: "Cari dan pilih...",
            width: '100%'
        });

        let map = L.map('map').setView([-6.200000, 106.816666], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        setTimeout(() => map.invalidateSize(), 1000);

        let patrolPoints = JSON.parse(document.getElementById('titik_patrol').value || '[]');
        patrolPoints.forEach(point => {
            if (point.lat && point.lng) {
                L.marker([point.lat, point.lng]).addTo(map);
            }
        });
    });

    function validateForm() {
        let lokasi = document.getElementById("lokasi").value;
        let pagi = document.getElementById("satpam_shift_pagi").value;
        let malam = document.getElementById("satpam_shift_malam").value;

        if (!lokasi || !pagi || !malam) {
            toastr.error("Harap lengkapi semua field yang wajib diisi.");
            return false;
        }
        return true;
    }
</script>
@endsection