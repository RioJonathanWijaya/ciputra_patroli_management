@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="relative overflow-auto sm:rounded-lg p-4">
        <x-breadcrumbs :items="[
            ['label' => 'Patroli', 'url' => route('admin.patroli.patroli')],
        ]" />
    <div class="flex flex-col sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between mb-10">
        <h1 class="text-3xl font-bold">Daftar Patroli</h1>
    </div>

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

    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="flex-1 space-y-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" 
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" 
                        placeholder="Cari patroli...">
                </div>
                
                <div>
                    <label for="status-filter" class="sr-only">Filter Status</label>
                    <div class="relative">
                        <select id="status-filter" name="status" 
                            class="appearance-none block w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out bg-white">
                            <option value="all">Semua Status</option>
                            <option value="Terlambat">Status: Terlambat</option>
                            <option value="Selesai">Status: Selesai</option>
                            <option value="Dalam Proses">Status: Dalam Proses</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-inner">
                <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                    <svg class="h-4 w-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Filter Tanggal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                        <div class="relative">
                            <input type="date" id="start_date" name="start_date" 
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 pl-3 pr-10 py-2 bg-white shadow-sm transition duration-150 ease-in-out">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                        <div class="relative">
                            <input type="date" id="end_date" name="end_date" 
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 pl-3 pr-10 py-2 bg-white shadow-sm transition duration-150 ease-in-out">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-4">
                    <button onclick="resetDateFilter()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center gap-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>
                    <button onclick="applyDateFilter()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2 shadow-sm">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
        <table class="min-w-full table-auto divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-[#1C3A6B] text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold whitespace-nowrap w-10 cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(0)">
                        No <span class="sort-icon" id="sort-icon-0">↕</span>
                    </th>
                    <th class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(1)">
                        Lokasi <span class="sort-icon" id="sort-icon-1">↕</span>
                    </th>
                    <th class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(2)">
                        Satpam <span class="sort-icon" id="sort-icon-2">↕</span>
                    </th>
                    <th class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(3)">
                        Tanggal <span class="sort-icon" id="sort-icon-3">↕</span>
                    </th>
                    <th class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(4)">
                        Status <span class="sort-icon" id="sort-icon-4">↕</span>
                    </th>
                    <th class="px-4 py-3 text-left font-semibold whitespace-nowrap w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="patroli-table-body" class="divide-y divide-gray-100">
                @forelse($paginatedItems as $index => $patroli)
                @php
                    $status = $patroli['status'];
                @endphp
                <tr class="hover:bg-gray-50 transition-all cursor-pointer"
                    data-lokasi="{{ $patroli['nama_lokasi'] }}"
                    data-satpam="{{ $patroli['nama_satpam'] }}"
                    data-tanggal="{{ $patroli['tanggal'] }}"
                    data-status="{{ $status }}"
                    onclick="openDetailModal(this)">
                    <td class="px-4 py-3 whitespace-nowrap">{{ $startItem + $index + 1 }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $patroli['nama_lokasi'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $patroli['nama_satpam'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $patroli['tanggal'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $status == 'Selesai' ? 'bg-green-100 text-green-800' : 
                               ($status == 'Terlambat' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap" onclick="event.stopPropagation()">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.patroli.show', $patroli['id']) }}"
                                class="text-blue-600 hover:text-blue-800 transition-all duration-200 transform hover:scale-110 relative group">
                                <i class="fa-solid fa-eye text-lg"></i>
                                <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
                                    Detail
                                </span>
                            </a>
                            <button type="button"
                                data-delete-url="{{ route('admin.patroli.destroy', $patroli['id']) }}"
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
                    <td colspan="6" class="text-center py-4 text-gray-500">Data patroli belum tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-pagination :items="$patroliData" :perPage="10" :currentPage="$currentPage ?? 1" />

    <div id="detailModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm justify-center items-center transition-all duration-300 ease-out">
        <div class="animate-fadeInUp bg-white rounded-2xl shadow-2xl p-6 w-full max-w-lg transform scale-95 transition-all duration-300 ease-out">
            <div class="flex items-center gap-3 mb-4">
                <i class="fa-solid fa-clipboard-list text-indigo-600 text-2xl"></i>
                <h2 class="text-2xl font-bold text-gray-800">Detail Patroli</h2>
            </div>
            <div class="space-y-4 text-sm sm:text-base">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-map-marker-alt text-rose-600"></i>
                    <span><strong>Lokasi:</strong> <span id="detail-lokasi" class="text-gray-700 font-medium"></span></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-user text-blue-600"></i>
                    <span><strong>Satpam:</strong> <span id="detail-satpam" class="text-gray-700 font-medium"></span></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar text-green-600"></i>
                    <span><strong>Tanggal:</strong> <span id="detail-tanggal" class="text-gray-700 font-medium"></span></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-info-circle text-yellow-600"></i>
                    <span><strong>Status:</strong> <span id="detail-status" class="text-gray-700 font-medium"></span></span>
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

<script src="{{ asset('js/table-filter.js') }}"></script>
<script src="{{ asset('js/patroli-table.js') }}"></script>
<script>
    let startDate = null;
    let endDate = null;

    document.getElementById('start_date').addEventListener('change', function(e) {
        startDate = e.target.value;
        filterPatroli();
    });

    document.getElementById('end_date').addEventListener('change', function(e) {
        endDate = e.target.value;
        filterPatroli();
    });

    function resetDateFilter() {
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        startDate = null;
        endDate = null;
        filterPatroli();
    }

    function applyDateFilter() {
        filterPatroli();
    }

    function filterPatroli() {
        const rows = document.querySelectorAll('#patroli-table-body tr');
        const searchTerm = document.getElementById('search').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;

        rows.forEach(row => {
            const lokasi = row.dataset.lokasi.toLowerCase();
            const satpam = row.dataset.satpam.toLowerCase();
            const tanggal = row.dataset.tanggal;
            const status = row.dataset.status;

            // Convert tanggal to Date object for comparison
            const rowDate = new Date(tanggal);
            const start = startDate ? new Date(startDate) : null;
            const end = endDate ? new Date(endDate) : null;

            // Check if date is within range
            const isInDateRange = (!start || rowDate >= start) && (!end || rowDate <= end);

            // Check if matches search and status
            const matchesSearch = lokasi.includes(searchTerm) || satpam.includes(searchTerm);
            const matchesStatus = statusFilter === 'all' || status === statusFilter;

            // Show/hide row based on all filters
            row.style.display = matchesSearch && matchesStatus && isInDateRange ? '' : 'none';
        });
    }

    function openDetailModal(row) {
        const lokasi = row.dataset.lokasi;
        const satpam = row.dataset.satpam;
        const tanggal = row.dataset.tanggal;
        const status = row.dataset.status;

        document.getElementById('detail-lokasi').textContent = lokasi;
        document.getElementById('detail-satpam').textContent = satpam;
        document.getElementById('detail-tanggal').textContent = tanggal;
        document.getElementById('detail-status').textContent = status;

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