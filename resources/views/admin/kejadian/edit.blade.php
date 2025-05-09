@extends('layouts.app')

@section('content')
<div class="space-y-6">
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

    <div class="bg-white shadow-lg border border-[#1C3A6B]/20 rounded-2xl p-6 animate-fade-in">
        <h2 class="text-2xl font-bold text-[#1C3A6B] mb-6">Edit Kejadian</h2>

        <form action="{{ route('admin.kejadian.update', $kejadian['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kejadian -->
                <div class="col-span-1">
                    <label for="nama_kejadian" class="block text-sm font-medium text-[#1C3A6B] mb-1">Nama Kejadian</label>
                    <input type="text" name="nama_kejadian" id="nama_kejadian" value="{{ old('nama_kejadian', $kejadian['nama_kejadian']) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0D7C5D] focus:outline-none">
                    @error('nama_kejadian')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi Kejadian -->
                <div class="col-span-1">
                    <label for="lokasi_kejadian" class="block text-sm font-medium text-[#1C3A6B] mb-1">Lokasi Kejadian</label>
                    <input type="text" name="lokasi_kejadian" id="lokasi_kejadian" value="{{ old('lokasi_kejadian', $kejadian['lokasi_kejadian']) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0D7C5D] focus:outline-none">
                    @error('lokasi_kejadian')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Kejadian -->
                <div class="col-span-1">
                    <label for="tanggal_kejadian" class="block text-sm font-medium text-[#1C3A6B] mb-1">Tanggal Kejadian</label>
                    <input type="datetime-local" name="tanggal_kejadian" id="tanggal_kejadian" 
                        value="{{ old('tanggal_kejadian', date('Y-m-d\TH:i', strtotime($kejadian['tanggal_kejadian']))) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0D7C5D] focus:outline-none">
                    @error('tanggal_kejadian')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-span-1">
                    <label for="status" class="block text-sm font-medium text-[#1C3A6B] mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0D7C5D] focus:outline-none">
                        <option value="pending" {{ old('status', $kejadian['status']) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status', $kejadian['status']) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $kejadian['status']) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-[#1C3A6B] mb-1">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0D7C5D] focus:outline-none">{{ old('keterangan', $kejadian['keterangan']) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Bukti -->
                <div class="col-span-2">
                    <label for="foto_bukti" class="block text-sm font-medium text-[#1C3A6B] mb-1">Foto Bukti</label>
                    <input type="file" name="foto_bukti" id="foto_bukti" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0D7C5D] focus:outline-none">
                    @error('foto_bukti')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    @if(isset($kejadian['foto_bukti_kejadian']) && count($kejadian['foto_bukti_kejadian']) > 0)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Foto Saat Ini:</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($kejadian['foto_bukti_kejadian'] as $foto)
                                    <div class="relative group">
                                        <img src="{{ $foto }}" alt="Foto Bukti" class="w-full h-32 object-cover rounded-xl">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 rounded-xl transition-opacity duration-200 flex items-center justify-center">
                                            <button type="button" class="text-white hover:text-red-500">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.kejadian.kejadian') }}" 
                    class="px-6 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0D7C5D]">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-[#1C3A6B] text-white rounded-xl hover:bg-[#172f5a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0D7C5D]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
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
</style>

<script>
    function closeAlertModal() {
        document.getElementById('alertModal').style.display = 'none';
    }
</script>
@endsection 