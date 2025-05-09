@extends('layouts.app')

@section('content')


<div class="min-h-screen bg-gray-50 p-4 md:p-6">
<div class="relative overflow-auto sm:rounded-lg p-4">
    <x-breadcrumbs :items="[
                ['label' => 'Kepala Satpam', 'url' => route('admin.kepala_satpam.kepala_satpam')],
            ]" />
    <div class="flex flex-col sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between mb-10">
        <h1 class="text-3xl font-bold">Data Kepala Satpam</h1>
        <div class="flex items-center gap-4">
            <div class="relative flex items-center bg-white rounded-lg shadow-sm border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all duration-200">
                <i class="fa-solid fa-search absolute left-3 text-gray-400"></i>
                <input type="text" 
                    class="search-kepala-satpam pl-10 pr-4 py-2.5 w-64 focus:outline-none bg-transparent" 
                    placeholder="Cari kepala satpam...">
            </div>
            <div class="relative">
                <select class="filter-kepala-satpam appearance-none bg-white rounded-lg shadow-sm border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 px-4 py-2.5 pr-10 focus:outline-none transition-all duration-200">
                    <option value="all">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="cuti">Cuti</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>
            <a href="{{ route('admin.satpam.create') }}" class="flex items-center gap-2 bg-[#1C3A6B] hover:bg-[#152B4F] text-white px-4 py-2.5 rounded-lg shadow-sm transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>ADD NEW</span>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-[#1C3A6B] text-white">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(0)">
                        No <span class="sort-icon" id="sort-icon-0">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(1)">
                        NIP <span class="sort-icon" id="sort-icon-1">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(2)">
                        Nama Lengkap <span class="sort-icon" id="sort-icon-2">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(3)">
                        Email <span class="sort-icon" id="sort-icon-3">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(4)">
                        Shift <span class="sort-icon" id="sort-icon-4">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(5)">
                        Jabatan <span class="sort-icon" id="sort-icon-5">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(6)">
                        No. Telepon <span class="sort-icon" id="sort-icon-6">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(7)">
                        Lokasi Jaga <span class="sort-icon" id="sort-icon-7">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(8)">
                        Status <span class="sort-icon" id="sort-icon-8">↕</span>
                    </th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody id="kepala-satpam-table-body" class="divide-y divide-gray-100">
                @if($satpamData)
                @php $no = 1; @endphp
                @foreach($satpamData as $key => $satpam)
                @php
                    if($satpam['status'] == 0){
                        $status = 'Aktif';
                    } else if ($satpam['status'] == 1){
                        $status = 'Cuti';
                    } else {
                        $status = 'Tidak Aktif';
                    };
                    $statusClass = match ($status) {
                        'Aktif' => 'bg-green-100 text-green-800',
                        'Cuti' => 'bg-orange-100 text-orange-800',
                        'Tidak Aktif' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition-all satpam-row cursor-pointer" 
                    data-url="{{ route('admin.satpam.detail', $satpam['satpam_id']) }}"
                    data-nama="{{ $satpam['nama'] ?? '-' }}"
                    data-nip="{{ $satpam['nip'] ?? '-' }}"
                    data-email="{{ $satpam['email'] ?? '-' }}"
                    data-shift="{{ $satpam['shift_text'] }}"
                    data-jabatan="{{ $satpam['jabatan_text'] }}"
                    data-telepon="{{ $satpam['nomor_telepon'] ?? '-' }}"
                    data-lokasi="{{ $satpam['lokasi'] ?? '-' }}"
                    data-status="{{ strtolower($status) }}">
                    <td class="px-4 py-3 whitespace-nowrap">{{ $no++ }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['nip'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['nama'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap max-w-[200px] truncate" title="{{ $satpam['email'] }}">{{ $satpam['email'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['shift_text'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['jabatan_text'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['nomor_telepon'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['lokasi'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ route('admin.satpam.detail', $satpam['satpam_id']) }}"
                            class="text-blue-600 hover:text-blue-800 underline transition-all duration-150">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="10" class="text-center py-4 text-gray-500">Data satpam belum tersedia.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    </div>
</div>

<script src="{{ asset('js/table-filter.js') }}"></script>
<script src="{{ asset('js/kepala-satpam-table.js') }}"></script>
<script>
    let currentSortColumn = -1;
    let sortDirection = 1; // 1 for ascending, -1 for descending

    function sortTable(columnIndex) {
        const table = document.getElementById('kepala-satpam-table-body');
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
            if (columnIndex === 8) {
                const statusOrder = { 'Aktif': 0, 'Cuti': 1, 'Tidak Aktif': 2 };
                return sortDirection * (statusOrder[aValue] - statusOrder[bValue]);
            }
            
            // Default string comparison
            return sortDirection * aValue.localeCompare(bValue);
        });
        
        // Reorder rows in the table
        rows.forEach(row => table.appendChild(row));
    }

    document.querySelectorAll('.satpam-row').forEach(row => {
        row.addEventListener('click', () => {
            const url = row.getAttribute('data-url');
            window.location.href = url;
        });
    });
</script>
@endsection