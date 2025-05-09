@extends('layouts.app')

@section('content')
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-xl flex flex-col items-center gap-4">
        <x-loader size="lg" color="primary" />
        <p class="text-gray-700 font-medium">Memuat data...</p>
    </div>
</div>

<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="relative overflow-auto sm:rounded-lg p-4">
        <x-breadcrumbs :items="[
            ['label' => 'Manajemen', 'url' => route('admin.manajemen.manajemen')],
        ]" />

        <div class="flex flex-col sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between mb-10">
            <h1 class="text-3xl font-bold">Data Manajemen</h1>
            <div class="flex items-center gap-4">
                <div class="relative flex items-center bg-white rounded-lg shadow-sm border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all duration-200">
                    <i class="fa-solid fa-search absolute left-3 text-gray-400"></i>
                    <input type="text" 
                        class="search-manajemen pl-10 pr-4 py-2.5 w-64 focus:outline-none bg-transparent" 
                        placeholder="Cari manajemen...">
                </div>
                <div class="relative">
                    <select class="filter-manajemen appearance-none bg-white rounded-lg shadow-sm border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 pr-10 focus:outline-none transition-all duration-200">
                        <option value="all">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="cuti">Cuti</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <a href="{{ route('admin.manajemen.create') }}" class="flex items-center gap-2 bg-[#1C3A6B] hover:bg-[#152B4F] text-white px-4 py-2.5 rounded-lg shadow-sm transition-all duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>ADD NEW</span>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-xl">
            <table class="min-w-full table-auto divide-y divide-gray-200 text-sm text-gray-700">
                <thead class="bg-[#1C3A6B] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left whitespace-nowrap font-semibold w-10 cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(0)">
                            No <span class="sort-icon" id="sort-icon-0">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left whitespace-nowrap font-semibold cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(1)">
                            NIP <span class="sort-icon" id="sort-icon-1">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left whitespace-nowrap font-semibold cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(2)">
                            Nama Lengkap <span class="sort-icon" id="sort-icon-2">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left whitespace-nowrap font-semibold cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(3)">
                            Email <span class="sort-icon" id="sort-icon-3">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left whitespace-nowrap font-semibold cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(4)">
                            Jabatan <span class="sort-icon" id="sort-icon-4">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left whitespace-nowrap font-semibold cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(5)">
                            No. Telepon <span class="sort-icon" id="sort-icon-5">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left whitespace-nowrap font-semibold cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(6)">
                            Status <span class="sort-icon" id="sort-icon-6">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left whitespace-nowrap font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="manajemen-table-body" class="divide-y divide-gray-100">
                    @if($manajemenData)
                        @php $no = 1; @endphp
                        @foreach($manajemenData as $key => $manajemen)
                        <tr class="hover:bg-gray-50 transition-all cursor-pointer manajemen-row" 
                            data-url="{{ route('admin.manajemen.detail', ['id' => $manajemen['manajemen_id']]) }}"
                            data-nama="{{ $manajemen['nama'] ?? '-' }}"
                            data-nip="{{ $manajemen['nip'] ?? '-' }}"
                            data-email="{{ $manajemen['email'] ?? '-' }}"
                            data-jabatan="{{ $manajemen['jabatan'] ?? '-' }}"
                            data-telepon="{{ $manajemen['nomor_telepon'] ?? '-' }}"
                            data-status="{{ strtolower($manajemen['status'] ?? '-') }}">
                            <td class="px-4 py-3 whitespace-nowrap">{{ $no++ }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $manajemen['nip'] ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $manajemen['nama'] ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap max-w-[220px] truncate">{{ $manajemen['email'] ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $manajemen['jabatan'] ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $manajemen['nomor_telepon'] ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $status = $manajemen['status'] ?? '-';
                                    $statusClass = match ($status) {
                                        'Aktif' => 'bg-green-100 text-green-800',
                                        'Cuti' => 'bg-orange-100 text-orange-800',
                                        'Tidak Aktif' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ route('admin.manajemen.detail', ['id' => $manajemen['manajemen_id']]) }}" class="action-button edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">Data manajemen belum tersedia.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/table-filter.js') }}"></script>
<script src="{{ asset('js/manajemen-table.js') }}"></script>
<script>
    let currentSortColumn = -1;
    let sortDirection = 1; // 1 for ascending, -1 for descending

    function sortTable(columnIndex) {
        const table = document.getElementById('manajemen-table-body');
        const rows = Array.from(table.getElementsByTagName('tr'));
        
        // Update sort direction and icons
        if (currentSortColumn === columnIndex) {
            sortDirection *= -1;
        } else {
            currentSortColumn = columnIndex;
            sortDirection = 1;
        }
        
        // Reset all sort icons
        document.querySelectorAll('.sort-icon').forEach(icon => {
            icon.textContent = '↕';
        });
        
        // Update current sort icon
        const currentIcon = document.getElementById(`sort-icon-${columnIndex}`);
        currentIcon.textContent = sortDirection === 1 ? '↑' : '↓';
        
        // Sort rows
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            // Special handling for status column
            if (columnIndex === 6) {
                const statusOrder = { 'Aktif': 0, 'Cuti': 1, 'Tidak Aktif': 2 };
                return sortDirection * (statusOrder[aValue] - statusOrder[bValue]);
            }
            
            // Default string comparison
            return sortDirection * aValue.localeCompare(bValue);
        });
        
        // Reorder rows in the table
        rows.forEach(row => table.appendChild(row));
    }

    document.querySelectorAll('.manajemen-row').forEach(row => {
        row.addEventListener('click', () => {
            const url = row.getAttribute('data-url');
            window.location.href = url;
        });
    });

    // Add page transition loader
    document.addEventListener('DOMContentLoaded', function() {
        // Show loader when clicking on navigation links
        document.querySelectorAll('a[href^="/admin/manajemen"]').forEach(link => {
            link.addEventListener('click', function(e) {
                // Don't show loader for same-page anchors
                if (this.getAttribute('href').includes('#')) return;
                
                e.preventDefault();
                const href = this.getAttribute('href');
                showPageLoader();
                
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            });
        });

        // Show loader when submitting forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                showPageLoader();
            });
        });
    });

    function showPageLoader() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }

    function hidePageLoader() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }

    // Hide loader when page is fully loaded
    window.addEventListener('load', function() {
        hidePageLoader();
    });
</script>
@endsection