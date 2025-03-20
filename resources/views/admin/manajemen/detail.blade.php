@extends('layouts.app')

@section('content')
<div class="space-y-6">
<div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between">
    <div class="flex items-center gap-4 w-full">
        <div class="flex items-center gap-4 flex-1">
            <img src="https://via.placeholder.com/80" class="rounded-full w-20 h-20 object-cover" alt="Manajemen Photo">
            <div>
                <h2 class="text-xl font-bold">{{ $manajemen['nama'] }}</h2>
                <p class="text-sm text-gray-500">{{ $manajemen['jabatan'] }}</p>
            </div>
        </div>

        <div class="h-16 w-px bg-gray-300 mx-6"></div>

        <div class="text-sm text-right text-gray-500 whitespace-nowrap">
            <p class="font-medium text-gray-600">Tanggal Bergabung</p>
            <p class="text-gray-900">{{ $manajemen['tanggal_bergabung'] ?? '-' }}</p>
        </div>
    </div>
</div>

    <div class="min-h-screen flex flex-col bg-gray-100 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p class="font-medium text-gray-600">Phone</p>
                        <p class="text-gray-900">{{ $manajemen['nomor_telepon'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Email</p>
                        <p class="text-gray-900">{{ $manajemen['email'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Gender</p>
                        <p class="text-gray-900">{{ $manajemen['jenis_kelamin'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Date of Birth</p>
                        <p class="text-gray-900">{{ $manajemen['tanggal_lahir'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Address</p>
                        <p class="text-gray-900">{{ $manajemen['alamat'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Marital Status</p>
                        <p class="text-gray-900">{{ $manajemen['status_pernikahan'] ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 border-b pb-2">Training & Awards</h3>
                @if (!empty($manajemen['pelatihan']))
                <div class="grid grid-cols-1 gap-4">
                    @foreach ($manajemen['pelatihan'] as $pelatihan)
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl shadow-sm hover:shadow-md transition-all">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-lg font-bold">
                            🏅
                        </div>
                        <div class="text-sm">
                            <p class="font-semibold text-gray-800">{{ $pelatihan['nama'] ?? '-' }}</p>
                            <p class="text-gray-600">Year: {{ $pelatihan['tahun'] ?? '-' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500 italic">No training or awards recorded.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection