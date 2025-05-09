@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="relative overflow-auto sm:rounded-lg p-4">
    <x-breadcrumbs :items="[
                ['label' => 'Kepala Satpam', 'url' => route('admin.kepala_satpam.kepala_satpam')],
                ['label' => 'Detail Kepala Satpam']
            ]" />
    <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <img src="https://via.placeholder.com/80" class="rounded-full w-20 h-20 object-cover" alt="Satpam Photo">
            <div>
                <h2 class="text-xl font-bold">{{ $satpam['nama'] }}</h2>
                <p class="text-sm text-gray-500">Senior Security | West Wing Sector</p>
            </div>
        </div>
        <div class="text-sm text-right text-gray-500">

        </div>
    </div>

    <div class="min-h-screen flex flex-col bg-gray-100 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p class="font-medium text-gray-600">Phone</p>
                        <p class="text-gray-900">{{ $satpam['nomor_telepon'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Email</p>
                        <p class="text-gray-900">{{ $satpam['email'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Gender</p>
                        <p class="text-gray-900">{{ $satpam['jenis_kelamin'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Date of Birth</p>
                        <p class="text-gray-900">{{ $satpam['tanggal_lahir'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Address</p>
                        <p class="text-gray-900">{{ $satpam['alamat'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Marital Status</p>
                        <p class="text-gray-900">{{ $satpam['status_perkawinan'] ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-5 border-b pb-2">Patrol Assignment</h3>
                <div class="space-y-3 text-sm text-gray-700">
                    <div>
                        <p class="font-medium text-gray-600">Current Patrol Area</p>
                        <p class="text-gray-900">Sector B - Main Gate</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Patrol Shift</p>
                        <p class="text-gray-900">Night Shift (18:00 - 02:00)</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Supervisor</p>
                        <p class="text-gray-900">Rudi Hartono</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Status</p>
                        <p class="text-gray-900">Active</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex items-center justify-between mb-5 border-b pb-2">
                    <h3 class="text-lg font-semibold text-gray-800">Account Information</h3>
                    <button class="text-blue-600 text-sm hover:underline">Edit</button>
                </div>
                <div class="space-y-3 text-sm text-gray-700">
                    <div>
                        <p class="font-medium text-gray-600">Bank Account</p>
                        <p class="text-gray-900">9876543210</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Account Name</p>
                        <p class="text-gray-900">Agus Santoso</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Bank</p>
                        <p class="text-gray-900">Bank Mandiri</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Tax ID</p>
                        <p class="text-gray-900">1234567890</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-600">Insurance Number</p>
                        <p class="text-gray-900">9988776655</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection