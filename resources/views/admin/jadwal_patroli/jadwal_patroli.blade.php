@extends('layouts.app')

@section('content')
<div class="relative overflow-auto sm:rounded-lg p-4">
    <div class="flex flex-col sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between mb-10">
        <h1 class="text-3xl font-bold">Jadwal Patroli</h1>
        <a href="{{ route('admin.jadwal_patroli.create') }}">
            <x-button color="secondary">ADD NEW</x-button>
        </a>
    </div>

    {{-- Toast Notification --}}
    @if(session('success') || session('error'))
    <div id="toast" class="fixed top-5 right-5 text-white font-semibold px-6 py-3 rounded-lg shadow-lg z-50
            {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}">
        {{ session('success') ?? session('error') }}
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) toast.style.display = 'none';
        }, 3000);
    </script>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
    <table class="min-w-full table-auto divide-y divide-gray-200 text-sm text-gray-700">
    <thead class="bg-[#1C3A6B] text-white">
        <tr>
            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap w-10">No</th>
            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Lokasi Cluster</th>
            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Satpam Shift Pagi</th>
            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Satpam Shift Siang</th>
            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap w-32">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
        @forelse($jadwalData as $index => $jadwal)
        <tr class="hover:bg-gray-50 transition-all cursor-pointer"
            data-lokasi="{{ $jadwal['nama_lokasi'] }}"
            data-shiftpagi="{{ $jadwal['nama_satpam_pagi'] }}"
            data-shiftsiang="{{ $jadwal['nama_satpam_malam'] }}"
            onclick="openDetailModal(this)">
            <td class="px-4 py-3 whitespace-nowrap">{{ $index + 1 }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $jadwal['nama_lokasi'] }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $jadwal['nama_satpam_pagi'] }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $jadwal['nama_satpam_malam'] }}</td>
            <td class="px-4 py-3 whitespace-nowrap" onclick="event.stopPropagation()">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.jadwal_patroli.edit', $jadwal['id']) }}"
                        class="text-blue-600 hover:text-blue-800 transition-all duration-200 transform hover:scale-110 relative group">
                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                        <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
                            Edit
                        </span>
                    </a>
                    <button type="button"
                        data-delete-url="{{ route('admin.jadwal_patroli.destroy', $jadwal['id']) }}"
                        onclick="showDeleteModalFromElement(this)"
                        class="text-red-600 hover:text-red-800 transition-all duration-200 transform hover:scale-110 relative group">
                        <i class="fa-solid fa-trash text-lg"></i>
                        <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
                            Delete
                        </span>
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-4 text-gray-500">Data jadwal belum tersedia.</td>
        </tr>
        @endforelse
    </tbody>
</table>

    </div>

    <div id="detailModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm justify-center items-center transition-all duration-300 ease-out">
        <div class="animate-fadeInUp bg-white rounded-2xl shadow-2xl p-6 w-full max-w-lg transform scale-95 transition-all duration-300 ease-out">
            <div class="flex items-center gap-3 mb-4">
                <i class="fa-solid fa-clipboard-list text-indigo-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-gray-800">Detail Jadwal Patroli</h2>
            </div>
            <div class="space-y-4 text-sm sm:text-base">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-map-marker-alt text-rose-600"></i>
                    <span><strong>Lokasi:</strong> <span id="detail-lokasi" class="text-gray-700 font-medium"></span></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-sun text-yellow-500"></i>
                    <span><strong>Satpam Shift Pagi:</strong> <span id="detail-shiftpagi" class="text-gray-700 font-medium"></span></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-moon text-indigo-600"></i>
                    <span><strong>Satpam Shift Siang:</strong> <span id="detail-shiftsiang" class="text-gray-700 font-medium"></span></span>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" id="closeDetailModal"
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-all duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm justify-center items-center transition-all duration-300 ease-out">
        <div class="animate-fadeInUp bg-white p-6 rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-all duration-300 ease-out">
            <h2 class="text-xl font-bold text-red-600 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> Konfirmasi Hapus
            </h2>
            <p class="text-gray-700 mb-5">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak bisa dibatalkan.</p>
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
</div>

<script>
    function openDetailModal(row) {
        const lokasi = row.dataset.lokasi;
        const shiftPagi = row.dataset.shiftpagi;
        const shiftSiang = row.dataset.shiftsiang;

        document.getElementById('detail-lokasi').textContent = lokasi;
        document.getElementById('detail-shiftpagi').textContent = shiftPagi;
        document.getElementById('detail-shiftsiang').textContent = shiftSiang;

        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    document.getElementById('closeDetailModal').addEventListener('click', () => {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
    });

    function showDeleteModalFromElement(el) {
        const url = el.getAttribute('data-delete-url');
        const form = document.getElementById('deleteForm');
        form.setAttribute('action', url);
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }
</script>
@endsection