@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-3xl p-8 space-y-8 animate-fade-in">
    <h2 class="text-3xl font-bold text-gray-800">Tambah Lokasi Baru</h2>

    <form action="{{ route('admin.lokasi.store') }}" method="POST" id="lokasiForm">
        @csrf

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lokasi</label>
                <input type="text" name="nama_lokasi" class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <input type="text" id="alamat" name="alamat" class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Lokasi di Peta</label>
                <div id="map" class="h-[300px] w-full rounded-xl border border-gray-300"></div>

                <div class="mt-4 flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="w-full rounded-xl border-gray-300 shadow-sm p-3" readonly>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="w-full rounded-xl border-gray-300 shadow-sm p-3" readonly>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-xl hover:bg-blue-800 transition font-semibold">Simpan Lokasi</button>
            </div>
        </div>
    </form>
</div>

<!-- Toast -->
<div id="validationToast" class="fixed top-6 right-6 bg-red-600 text-white px-4 py-3 rounded-xl shadow-xl hidden z-50">
    Harap isi semua data wajib terlebih dahulu!
</div>

<!-- Modal Konfirmasi -->
<div id="confirmationModal" class="fixed inset-0 bg-black/50 z-40 hidden flex items-center justify-center">
    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-4 animate-fade-in">
        <h2 class="text-xl font-bold text-gray-800">Konfirmasi</h2>
        <p class="text-sm text-gray-600">Apakah Anda yakin ingin menyimpan data lokasi ini?</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeConfirmationModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl text-sm">Batal</button>
            <button onclick="submitLokasiForm()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-semibold">Ya, Simpan</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
const defaultLat = -6.200000;
const defaultLng = 106.816666;

const map = L.map('map').setView([defaultLat, defaultLng], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
document.getElementById('latitude').value = defaultLat;
document.getElementById('longitude').value = defaultLng;

function reverseGeocode(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('alamat').value = data.display_name || '';
        })
        .catch(() => {
            document.getElementById('alamat').value = '';
        });
}

marker.on('dragend', () => {
    const pos = marker.getLatLng();
    document.getElementById('latitude').value = pos.lat.toFixed(6);
    document.getElementById('longitude').value = pos.lng.toFixed(6);
    reverseGeocode(pos.lat, pos.lng);
});

map.on('click', function(e) {
    marker.setLatLng(e.latlng);
    document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
    document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
    reverseGeocode(e.latlng.lat, e.latlng.lng);
});

reverseGeocode(defaultLat, defaultLng);

setTimeout(() => map.invalidateSize(), 300);

const form = document.getElementById('lokasiForm');
form.addEventListener('submit', function(e) {
    e.preventDefault();
    const required = form.querySelectorAll('input[required]');
    let valid = true;
    required.forEach(f => { if (!f.value.trim()) valid = false; });

    if (!valid) {
        const toast = document.getElementById('validationToast');
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    } else {
        showConfirmationModal();
    }
});

function showConfirmationModal() {
    document.getElementById('confirmationModal').classList.remove('hidden');
}
function closeConfirmationModal() {
    document.getElementById('confirmationModal').classList.add('hidden');
}
function submitLokasiForm() {
    closeConfirmationModal();
    form.submit();
}
</script>
@endsection