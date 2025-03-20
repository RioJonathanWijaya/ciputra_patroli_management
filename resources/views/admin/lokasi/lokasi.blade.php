@extends('layouts.app')

@section('content')
<div class="relative overflow-auto sm:rounded-lg p-4">
    <div class="flex flex-col sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between mb-10">
        <h1 class="text-3xl font-bold">Data Lokasi</h1>
        <a href="{{ route('admin.lokasi.create') }}">
            <x-button color="secondary">ADD NEW</x-button>
        </a>
    </div>

    <x-card class="flex flex-wrap items-center gap-4 mb-10">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-semibold mb-1 text-gray-700">What are you looking for?</label>
            <x-input name="search" type="text" placeholder="Search location..." />
        </div>
        <div class="min-w-[150px] mt-5">
            <x-button>SEARCH</x-button>
        </div>
    </x-card>

    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-[#1C3A6B] text-white">
                <tr>
                    <th class="px-6 py-3 text-left">No</th>
                    <th class="px-6 py-3 text-left">Nama Lokasi</th>
                    <th class="px-6 py-3 text-left">Alamat</th>
                    <th class="px-6 py-3 text-left">Deskripsi</th>
                    <th class="px-6 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @if($lokasiData)
                @foreach($lokasiData as $index => $location)
                <tr>
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">{{ $location['nama_lokasi'] }}</td>
                    <td class="px-6 py-4">{{ $location['alamat'] }}</td>
                    <td class="px-6 py-4">{{ $location['deskripsi'] }}</td>
                    <td class="px-6 py-4 flex gap-3">
                        <button
                            class="text-blue-600 hover:text-blue-800 transition-all duration-200 transform hover:scale-110 relative group open-edit-modal"
                            data-id="{{ $location['id'] }}"
                            data-nama="{{ $location['nama_lokasi'] }}"
                            data-alamat="{{ $location['alamat'] }}"
                            data-deskripsi="{{ $location['deskripsi'] }}">
                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                            <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">Edit</span>
                        </button>

                        <form action="{{ route('admin.lokasi.destroy', $location['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-red-600 hover:text-red-800 transition-all duration-200 transform hover:scale-110 relative group">
                                <i class="fa-solid fa-trash text-lg"></i>
                                <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">Delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6" class="text-center py-4">No data available</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 justify-center items-center">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg">
        <h2 class="text-xl font-bold mb-4">Edit Lokasi</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-id">

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama Lokasi</label>
                <x-input name="nama_lokasi" id="edit-nama" />
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Alamat</label>
                <x-input name="alamat" id="edit-alamat" />
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="edit-deskripsi" class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" id="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-[#0D7C5D] hover:bg-[#09684c] text-white font-semibold px-4 py-2 rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');

    document.querySelectorAll('.open-edit-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nama = btn.dataset.nama;
            const alamat = btn.dataset.alamat;
            const deskripsi = btn.dataset.deskripsi;

            form.action = `/admin/lokasi/${id}`;
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-alamat').value = alamat;
            document.getElementById('edit-deskripsi').value = deskripsi;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    document.getElementById('closeModal').addEventListener('click', () => {
        modal.classList.add('hidden');
    });
</script>
@endsection