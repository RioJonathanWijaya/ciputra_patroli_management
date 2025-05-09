@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="relative overflow-auto sm:rounded-lg p-4">
        <x-breadcrumbs :items="[
            ['label' => 'Profile']
        ]" />

        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Profile Settings</h1>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ $user['displayName'] ?? '' }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ $user['email'] ?? '' }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ $user['phoneNumber'] ?? '' }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 