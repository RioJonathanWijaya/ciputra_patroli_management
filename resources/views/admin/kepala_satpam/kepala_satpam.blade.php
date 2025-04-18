@extends('layouts.app')

@section('content')


<div class="relative overflow-auto sm:rounded-lg p-4">
    <div class="flex flex-col sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between mb-10">
        <h1 class="text-3xl font-bold">Data Kepala Satpam</h1>
        <a href="{{ route('admin.satpam.create') }}">
            <x-button color="secondary">ADD NEW</x-button>
        </a>
    </div>

    <x-card class="flex flex-wrap items-center gap-4 mb-10">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-semibold mb-1 text-gray-700">What are you looking for?</label>
            <x-input name="input" type="text" placeholder="Search for category, name, company, etc" />
        </div>

        <div class="min-w-[150px]">
            <label class="block text-sm font-semibold mb-1 text-gray-700">Category</label>
            <x-select name="kategori" :options="['All' => 'All', 'Category A' => 'Category A', 'Category B' => 'Category B', 'Category C' => 'Category C']" />
        </div>

        <div class="min-w-[150px]">
            <label class="block text-sm font-semibold mb-1 text-gray-700">Status</label>
            <x-select name="kategori" :options="['All' => 'All', 'Category A' => 'Category A', 'Category B' => 'Category B', 'Category C' => 'Category C']" />

        </div>

        <div class="min-w-[150px] mt-5">
            <x-button>SEARCH</x-button>
        </div>
    </x-card>

    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-[#1C3A6B] text-white">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">No</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">NIP</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">Nama Lengkap</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">Email</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">Shift</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">Jabatan</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">No. Telepon</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">Lokasi Jaga</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">Status</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @if($satpamData)
                @php $no = 1; @endphp
                @foreach($satpamData as $key => $satpam)
                <tr class="hover:bg-gray-50 transition-all satpam-row cursor-pointer" data-url="{{ route('admin.satpam.detail', $satpam['satpam_id']) }}">
                    <td class="px-4 py-3 whitespace-nowrap">{{ $no++ }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['nip'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['nama'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap max-w-[200px] truncate" title="{{ $satpam['email'] }}">{{ $satpam['email'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['shift_text'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['jabatan_text'] }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['nomor_telepon'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $satpam['lokasi'] ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
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

<script>
    document.querySelectorAll('.satpam-row').forEach(row => {
        row.addEventListener('click', () => {
            const url = row.getAttribute('data-url');
            window.location.href = url;
        });
    });
</script>
@endsection