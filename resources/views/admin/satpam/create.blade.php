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
    {{-- Page Title --}}
    <div class="text-3xl font-bold text-[#1C3A6B]">Tambah Satpam</div>

    <form id="satpamForm" action="{{ route('admin.satpam.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Upload Foto --}}
            <div class="col-span-1 bg-white shadow-lg border border-[#1C3A6B]/20 rounded-2xl p-5 animate-fade-in">
                <h3 class="text-xl font-semibold text-[#1C3A6B] mb-4">Foto Profil</h3>
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 mb-4 transition hover:scale-105 duration-300 border border-[#0D7C5D]/30 rounded-xl overflow-hidden">
                        <img id="profileImage" src="https://via.placeholder.com/160" alt="Profile" class="w-full h-full object-cover">
                        <button type="button" onclick="removePhoto()" class="absolute top-0 right-0 bg-white rounded-full p-1 shadow hover:bg-red-100 transition">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <input type="file" name="photo" id="uploadPhotoInput" class="hidden" onchange="previewPhoto(event)">
                    <button type="button" onclick="document.getElementById('uploadPhotoInput').click()" class="bg-[#0D7C5D] hover:bg-[#0b684e] text-white px-5 py-2 rounded-xl transition font-medium">Upload Photo</button>
                </div>
            </div>

            {{-- Informasi Pribadi --}}
            <div class="col-span-2 bg-white shadow-lg border border-[#1C3A6B]/20 rounded-2xl p-6 animate-fade-in space-y-6">
                <h3 class="text-xl font-semibold text-[#1C3A6B] border-b border-gray-200 pb-2">Informasi Pribadi</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Email</label>
                        <p class="text-sm text-gray-500">(Gunakan NIP satpam sebagai email!)</p>
                        <input type="email" name="email" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">NIP</label>
                        <input type="text" name="nip" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[#1C3A6B]">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#1C3A6B]">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#1C3A6B]">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                                <option>Laki-laki</option>
                                <option>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#1C3A6B]">Status Pernikahan</label>
                            <select name="status_pernikahan" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                                <option>Menikah</option>
                                <option>Belum Menikah</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Alamat</label>
                        <input type="text" name="alamat" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[#1C3A6B]">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#1C3A6B]">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Satpam --}}
            <div class="col-span-3 bg-white shadow-lg border border-[#1C3A6B]/20 rounded-2xl p-6 animate-fade-in space-y-6">
                <h3 class="text-xl font-semibold text-[#1C3A6B] border-b border-gray-200 pb-2">Data Satpam</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Jabatan</label>
                        <select name="jabatan" id="jabatanSelect" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                            <option>Satpam</option>
                            <option>Kepala Shift</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Shift</label>
                        <select name="shift" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                            <option value="Pagi">Pagi</option>
                            <option value="Malam">Malam</option>
                        </select>
                    </div>
                    <div id="supervisorField" class="transition-all duration-500 ease-in-out overflow-hidden max-h-[200px] opacity-100">
                        <label class="text-sm font-medium text-[#1C3A6B]">Supervisor</label>
                        <select name="supervisor" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                            <option value="">Pilih Supervisor</option>
                            @foreach($kepalaSatpamData as $supervisor)
                            <option value="{{ $supervisor['nama'] }}">{{ $supervisor['nip'] }} - {{ $supervisor['nama'] }} - {{ $supervisor['shift'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Tanggal Bergabung</label>
                        <input type="date" name="tanggal_bergabung" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#1C3A6B]">Password</label>
                        <input type="password" name="password" class="w-full border border-gray-300 p-2 rounded mt-1 focus:ring-2 focus:ring-[#0D7C5D]" required>
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
        <p class="text-gray-700">Apakah Anda yakin data Satpam sudah benar dan ingin disimpan?</p>
        <div class="flex justify-end gap-3 pt-4">
            <button onclick="closeModal()" class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">Kembali</button>
            <button onclick="document.getElementById('satpamForm').submit()" class="px-4 py-2 rounded-xl bg-[#0D7C5D] hover:bg-[#0b684e] text-white font-semibold">Ya, Simpan Data</button>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
            const $jabatanSelect = $('#jabatanSelect');
        const $supervisorField = $('#supervisorField');
        const $supervisorSelect = $('#supervisorSelect');

        function toggleSupervisorField() {
            if ($jabatanSelect.val() === 'Kepala Shift') {
                $supervisorSelect.val('').prop('disabled', true);
            $supervisorField
                .removeClass('max-h-[200px] opacity-100')
                .addClass('max-h-0 opacity-0');
            } else {
                $supervisorSelect.prop('disabled', false).val('');
            $supervisorField
                .removeClass('max-h-0 opacity-0')
                .addClass('max-h-[200px] opacity-100');
            }
        }

        toggleSupervisorField();

        $jabatanSelect.on('change', toggleSupervisorField);

    function previewPhoto(event) {
        const reader = new FileReader();
        reader.onload = () => {
            document.getElementById('profileImage').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function removePhoto() {
        document.getElementById('profileImage').src = 'https://via.placeholder.com/160';
        document.getElementById('uploadPhotoInput').value = null;
    }

    function openModal() {
        document.getElementById('confirmModal').classList.remove('hidden');
        document.getElementById('confirmModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.remove('flex');
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function closeAlertModal() {
        const modal = document.getElementById('alertModal');
        if (modal) modal.remove();
    }
</script>

<!-- Animations -->
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    .animate-slide-in-down {
        animation: slideInDown 0.4s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection