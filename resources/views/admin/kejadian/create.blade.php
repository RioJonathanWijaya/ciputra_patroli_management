@extends('layouts.app')

@section('content')

@if (session('success') || session('error'))
<div id="alertModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl space-y-4 animate-fade-in">
        <div class="flex items-center gap-3">
            @if(session('success'))
            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <h2 class="text-lg font-semibold text-green-700">Sukses</h2>
            @elseif(session('error'))
            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <h2 class="text-lg font-semibold text-red-700">Terjadi Kesalahan</h2>
            @endif
        </div>

        <p class="text-gray-700 text-sm">
            {{ session('success') ?? session('error') }}
        </p>

        <div class="text-right">
            <button onclick="closeAlertModal()" class="px-4 py-2 bg-[#1C3A6B] hover:bg-[#172f5a] text-white rounded-xl text-sm font-semibold">
                Tutup
            </button>
        </div>
    </div>
</div>
@endif

<div class="max-w-7xl mx-auto p-6 space-y-6">
    <div class="text-3xl font-bold text-[#1C3A6B]">Tambah Kejadian</div>

    <form id="kejadianForm" action="{{ route('admin.kejadian.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="col-span-1 bg-white shadow-lg border border-[#1C3A6B]/20 rounded-2xl p-5 animate-fade-in">
                <h3 class="text-xl font-semibold text-[#1C3A6B] mb-4">Foto Bukti Kejadian<span class="text-red-500">*</span></h3>
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 mb-4 transition hover:scale-105 duration-300 border border-[#0D7C5D]/30 rounded-xl overflow-hidden">
                        <img id="kejadianImage" src="{{ asset('images/default-image.png') }}" alt="Bukti Kejadian" class="w-full h-full object-cover">
                        <button type="button" onclick="removePhoto()" class="absolute top-0 right-0 bg-white rounded-full p-1 shadow hover:bg-red-100 transition">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <input type="file" name="foto_bukti" id="uploadPhotoInput" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                    <button type="button" onclick="document.getElementById('uploadPhotoInput').click()" class="bg-[#0D7C5D] hover:bg-[#0b684e] text-white px-5 py-2 rounded-xl transition font-medium">Upload Photo</button>
                    <p class="mt-2 text-sm text-gray-500">Format: JPG, PNG (max. 2MB)</p>
                    @error('foto_bukti')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="col-span-2 bg-white shadow-lg border border-[#1C3A6B]/20 rounded-2xl p-6 animate-fade-in space-y-6">
                <h3 class="text-xl font-semibold text-[#1C3A6B] border-b border-gray-200 pb-2">Informasi Kejadian<span class="text-red-500">*</span></h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Nama Kejadian<span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kejadian" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Lokasi Kejadian<span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi_kejadian" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[#1C3A6B]">Tanggal Kejadian<span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="tanggal_kejadian" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#1C3A6B]">Tipe Kejadian<span class="text-red-500">*</span></label>
                            <select name="tipe_kejadian" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                                <option value="Ringan">Ringan</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Berat">Berat</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Keterangan<span class="text-red-500">*</span></label>
                        <textarea name="keterangan" rows="3" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required></textarea>
                    </div>
                </div>
            </div>

            <div class="col-span-3 bg-white shadow-lg border border-[#1C3A6B]/20 rounded-2xl p-6 animate-fade-in space-y-6">
                <h3 class="text-xl font-semibold text-[#1C3A6B] border-b border-gray-200 pb-2">Informasi Korban (Opsional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Nama Korban</label>
                        <input type="text" name="nama_korban" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Alamat Korban</label>
                        <input type="text" name="alamat_korban" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Keterangan Korban</label>
                        <textarea name="keterangan_korban" rows="2" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-span-3 bg-white shadow-lg border border-[#1C3A6B]/20 rounded-2xl p-6 animate-fade-in space-y-6">
                <h3 class="text-xl font-semibold text-[#1C3A6B] border-b border-gray-200 pb-2">Status Kejadian</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Status</label>
                        <select name="status" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Ditunda">Ditunda</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Satpam Penanggung Jawab</label>
                        <select name="satpam_id" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                            <option value="">Pilih Satpam</option>
                            @foreach($satpamData as $satpam)
                            <option value="{{ $satpam['id'] }}">{{ $satpam['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-span-3 text-right">
                <button type="button" onclick="openModal()" class="bg-[#1C3A6B] hover:bg-[#172f5a] text-white px-6 py-2 rounded-xl font-semibold transition">
                    Simpan Data
                </button>
            </div>
        </div>
    </form>
</div>

<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full space-y-4">
        <h2 class="text-xl font-bold text-[#1C3A6B]">Konfirmasi Simpan Data</h2>
        <p class="text-gray-700">Apakah Anda yakin data Kejadian sudah benar dan ingin disimpan?</p>
        <div class="flex justify-end gap-3 pt-4">
            <button onclick="closeModal()" class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">Kembali</button>
            <button onclick="document.getElementById('kejadianForm').submit()" class="px-4 py-2 rounded-xl bg-[#0D7C5D] hover:bg-[#0b684e] text-white font-semibold">Ya, Simpan Data</button>
        </div>
    </div>
</div>

<script>
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('kejadianImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function removePhoto() {
        document.getElementById('kejadianImage').src = "{{ asset('images/default-image.png') }}";
        document.getElementById('uploadPhotoInput').value = '';
    }

    function openModal() {
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function closeAlertModal() {
        document.getElementById('alertModal').classList.add('hidden');
    }
</script>
@endsection 