@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    @if(session('success') || session('error'))
    <div id="toast" class="fixed top-5 right-5 text-white font-medium px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300
            {{ session('success') ? 'bg-green-500' : 'bg-red-500' }} flex items-center gap-3 animate-slide-in">
        @if(session('success'))
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        @else
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        @endif
        <span>{{ session('success') ?? session('error') }}</span>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.remove('animate-slide-in');
                toast.classList.add('animate-fade-out');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    </script>
    @endif

    <!-- <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl flex flex-col items-center gap-4">
            <x-loader size="lg" color="primary" />
            <p class="text-gray-700 font-medium">Memuat data...</p>
        </div>
    </div> -->
    <x-breadcrumbs :items="[
                ['label' => 'Kejadian', 'url' => route('admin.kejadian.kejadian')],
            ]" />

    <div class="flex flex-col sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between mb-10">
        <div class="title">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Manajemen Laporan Kejadian</h1>
            <p class="text-gray-600 mt-1">Daftar laporan kejadian yang tercatat dalam sistem</p>
        </div>
        <a href="{{ route('admin.kejadian.create') }}">
            <x-button color="secondary">ADD NEW</x-button>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Search and Status Filter -->
            <div class="flex-1 space-y-4">
                <!-- Search with magnifying glass icon -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" 
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" 
                        placeholder="Cari laporan...">
                </div>
                
                <!-- Status filter with dropdown -->
                <div>
                    <label for="status-filter" class="sr-only">Filter Status</label>
                    <div class="relative">
                        <select id="status-filter" name="status" 
                            class="appearance-none block w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out bg-white">
                            <option value="all">Semua Status</option>
                            <option value="baru">Status: Baru</option>
                            <option value="proses">Status: Proses</option>
                            <option value="selesai">Status: Selesai</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Date Range Filter -->
            <div class="flex-1 bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-inner">
                <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                    <svg class="h-4 w-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Filter Tanggal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Start Date -->
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
                    
                    <!-- End Date -->
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
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-4">
                    <button onclick="resetDateFilter()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center gap-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2 shadow-sm">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
        <table class="min-w-full table-auto divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-[#1C3A6B] text-white">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a] transition-colors duration-200 whitespace-nowrap" onclick="sortTable(0)">
                        No <span class="sort-icon ml-1" id="sort-icon-0">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a] transition-colors duration-200 whitespace-nowrap" onclick="sortTable(1)">
                        Nama Kejadian <span class="sort-icon ml-1" id="sort-icon-1">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a] transition-colors duration-200 whitespace-nowrap" onclick="sortTable(2)">
                        Tanggal <span class="sort-icon ml-1" id="sort-icon-2">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a] transition-colors duration-200 whitespace-nowrap" onclick="sortTable(3)">
                        Lokasi <span class="sort-icon ml-1" id="sort-icon-3">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a] transition-colors duration-200 whitespace-nowrap" onclick="sortTable(4)">
                        Tipe <span class="sort-icon ml-1" id="sort-icon-4">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a] transition-colors duration-200 whitespace-nowrap" onclick="sortTable(5)">
                        Waktu Laporan <span class="sort-icon ml-1" id="sort-icon-5">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a] transition-colors duration-200 whitespace-nowrap" onclick="sortTable(6)">
                        Status <span class="sort-icon ml-1" id="sort-icon-6">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="kejadian-body">
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 bg-white border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center">
        <div class="text-sm text-gray-700 mb-2 sm:mb-0">
            Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">10</span> dari <span class="font-medium">24</span> hasil
        </div>
        <div class="flex space-x-1">
            <button class="px-3 py-1 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50">
                Previous
            </button>
            <button class="px-3 py-1 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50">
                Next
            </button>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md mx-4 transform transition-all duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h3>
            <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus laporan kejadian ini?</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                Batal
            </button>
            <button onclick="confirmDelete()" class="px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition-colors duration-200">
                Hapus
            </button>
        </div>
    </div>
</div>
</div>

<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const firebaseConfig = {
            apiKey: "{{ config('services.firebase.client.api_key') }}",
            authDomain: "{{ config('services.firebase.client.auth_domain') }}",
            databaseURL: "{{ config('services.firebase.client.database_url') }}",
            projectId: "{{ config('services.firebase.client.project_id') }}",
            storageBucket: "{{ config('services.firebase.client.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.client.messaging_sender_id') }}",
            appId: "{{ config('services.firebase.client.app_id') }}"
        };

        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();
        const renderedIds = new Set();

        let currentFilter = 'all';
        let currentSearch = '';
        let startDate = null;
        let endDate = null;

        const searchInput = document.querySelector('.search-input');
        const filterSelect = document.querySelector('.filter-select');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const kejadianBody = document.getElementById("kejadian-body");

        fetchKejadianData();
        listenForRealtimeUpdates();
        setupEventListeners();

        function setupEventListeners() {
            searchInput.addEventListener('input', debounce(function() {
                currentSearch = this.value.toLowerCase();
                filterKejadian();
            }, 300));

            filterSelect.addEventListener('change', function() {
                currentFilter = this.value;
                filterKejadian();
            });

            startDateInput.addEventListener('change', function() {
                startDate = this.value ? new Date(this.value) : null;
                filterKejadian();
            });

            endDateInput.addEventListener('change', function() {
                endDate = this.value ? new Date(this.value) : null;
                filterKejadian();
            });
        }

        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this,
                    args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        function filterKejadian() {
            const rows = kejadianBody.querySelectorAll('tr');

            rows.forEach(row => {
                const status = row.getAttribute('data-status').toLowerCase();
                const rowText = row.textContent.toLowerCase();
                const rowDate = new Date(row.getAttribute('data-date'));

                const filterMatch = currentFilter === 'all' || status === currentFilter;
                const searchMatch = currentSearch === '' || rowText.includes(currentSearch);
                const dateMatch = (!startDate || rowDate >= startDate) && (!endDate || rowDate <= endDate);

                row.style.display = (filterMatch && searchMatch && dateMatch) ? '' : 'none';
            });
        }

        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        function fetchKejadianData() {
            showLoading();
            fetch("{{ route('admin.kejadian.index') }}")
                .then(response => response.json())
                .then(responseData => {
                    const kejadianList = responseData.data;

                    if (Array.isArray(kejadianList)) {
                        kejadianList.sort((a, b) => {
                            const dateA = new Date(a.waktu_laporan);
                            const dateB = new Date(b.waktu_laporan);
                            return dateB - dateA;
                        });

                        kejadianList.forEach((kejadian, index) => {
                            addKejadianToTable(kejadian, index + 1);
                            renderedIds.add(kejadian.id);
                        });
                    } else {
                        console.warn("Unexpected response format:", responseData);
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error("Error fetching kejadian data:", error);
                    showErrorToast("Gagal memuat data kejadian");
                    hideLoading();
                });
        }

        function listenForRealtimeUpdates() {
            database.ref('kejadian')
                .orderByChild('waktu_laporan')
                .limitToLast(50)
                .on('child_added', (snapshot) => {
                    const newKejadian = snapshot.val();
                    const kejadianId = snapshot.key;

                    if (renderedIds.has(kejadianId)) return;

                    database.ref('foto_bukti_kejadian')
                        .orderByChild('kejadian_id')
                        .equalTo(kejadianId)
                        .once('value', (fotoSnapshot) => {
                            const fotos = [];
                            fotoSnapshot.forEach((foto) => {
                                fotos.push(foto.val().url);
                            });

                            const kejadianObj = {
                                id: kejadianId,
                                data: newKejadian,
                                foto_bukti_kejadian: fotos
                            };
                            const firstRow = kejadianBody.firstChild;
                            if (firstRow) {
                                addKejadianToTable(kejadianObj, 1);
                                const rows = kejadianBody.getElementsByTagName('tr');
                                for (let i = 1; i < rows.length; i++) {
                                    rows[i].cells[0].textContent = i + 1;
                                }
                            } else {
                                addKejadianToTable(kejadianObj);
                            }
                            
                            renderedIds.add(kejadianId);
                            showSuccessToast("Laporan baru diterima");
                        });
                });
        }

        function addKejadianToTable(kejadian, number = null) {
            const rowNumber = number ?? kejadianBody.children.length + 1;
            const status = kejadian.status ? kejadian.status.toLowerCase() : '';
            const date = kejadian.tanggal_kejadian ? new Date(kejadian.tanggal_kejadian) : new Date();

            const newRow = document.createElement("tr");
            newRow.className = "hover:bg-gray-50 transition-colors duration-200";
            newRow.setAttribute('data-status', status);
            newRow.setAttribute('data-date', date.toISOString());

            newRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${rowNumber}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${kejadian.nama_kejadian || '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${kejadian.tanggal_kejadian ? new Date(kejadian.tanggal_kejadian).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${kejadian.lokasi_kejadian || '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="${getBadgeClass(kejadian.tipe_kejadian)} px-2 py-1 text-xs font-semibold rounded-full">
                    ${kejadian.tipe_kejadian || 'Tidak diketahui'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${kejadian.waktu_laporan ? new Date(kejadian.waktu_laporan).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="status-badge ${status}">
                    ${kejadian.status || 'Tidak diketahui'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end items-center gap-3">
                    <a href="/admin/kejadian/${kejadian.id}" class="action-button view" title="Lihat Detail">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    <a href="/admin/kejadian/${kejadian.id}/edit" class="action-button edit" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <button onclick="showDeleteModal('${kejadian.id}')" class="action-button delete" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
        `;

            kejadianBody.appendChild(newRow);
            filterKejadian();
        }

        function resetDateFilter() {
            startDateInput.value = '';
            endDateInput.value = '';
            startDate = null;
            endDate = null;
            filterKejadian();
        }
    });

    function getBadgeClass(tipe) {
        if (!tipe || typeof tipe !== "string") return "bg-gray-100 text-gray-800";

        tipe = tipe.toLowerCase();
        if (tipe.includes("ringan")) return "bg-green-100 text-green-800";
        if (tipe.includes("sedang")) return "bg-yellow-100 text-yellow-800";
        if (tipe.includes("berat")) return "bg-red-100 text-red-800";

        return "bg-gray-100 text-gray-800";
    }

    function getStatusClass(status) {
        if (!status || typeof status !== "string") return "bg-gray-100 text-gray-800";

        status = status.toLowerCase();
        if (status.includes("selesai")) return "bg-green-100 text-green-800";
        if (status.includes("proses")) return "bg-yellow-100 text-yellow-800";
        if (status.includes("baru")) return "bg-blue-100 text-blue-800";

        return "bg-gray-100 text-gray-800";
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }

    function showDeleteModal(id) {
        currentKejadianId = id;
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        hideDeleteModal();
    }

    function confirmDelete() {
        if (!currentKejadianId) return;
        
        window.location.href = `/admin/kejadian/${currentKejadianId}/delete`;
    }

    function printDetail() {
        const modalContent = document.querySelector('#detailModal > div > div').innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = `
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-4">Detail Laporan Kejadian</h1>
                ${modalContent}
            </div>
        `;

        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload();
    }

    function showSuccessToast(message) {
        showToast(message, 'green');
    }

    function showErrorToast(message) {
        showToast(message, 'red');
    }

    function showToast(message, color) {
        const toast = document.createElement('div');
        toast.className = `fixed top-5 right-5 text-white font-medium px-6 py-3 rounded-lg shadow-lg z-50 bg-${color}-500 flex items-center gap-3 animate-slide-in`;
        toast.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${color === 'green' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}" />
            </svg>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('animate-slide-in');
            toast.classList.add('animate-fade-out');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    let currentSortColumn = -1;
    let sortDirection = 1; // 1 for ascending, -1 for descending

    function sortTable(columnIndex) {
        const table = document.getElementById('kejadian-body');
        const rows = Array.from(table.getElementsByTagName('tr'));
        
        if (currentSortColumn === columnIndex) {
            sortDirection *= -1;
        } else {
            currentSortColumn = columnIndex;
            sortDirection = 1;
        }
        
        document.querySelectorAll('.sort-icon').forEach(icon => {
            icon.textContent = '↕';
        });
        
        const currentIcon = document.getElementById(`sort-icon-${columnIndex}`);
        currentIcon.textContent = sortDirection === 1 ? '↑' : '↓';
        
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            if (columnIndex === 6) {
                const statusOrder = { 'Selesai': 0, 'Proses': 1, 'Baru': 2 };
                return sortDirection * (statusOrder[aValue] - statusOrder[bValue]);
            }
            
            if (columnIndex === 4) {
                const tipeOrder = { 'Ringan': 0, 'Sedang': 1, 'Berat': 2 };
                return sortDirection * (tipeOrder[aValue] - tipeOrder[bValue]);
            }
            
            if (columnIndex === 2 || columnIndex === 5) {
                const aDate = new Date(aValue);
                const bDate = new Date(bValue);
                return sortDirection * (aDate - bDate);
            }
            
            return sortDirection * aValue.localeCompare(bValue);
        });
        
        rows.forEach(row => table.appendChild(row));
    }

</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out forwards;
    }

    .animate-fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }

    /* Table Styles */
    .table-auto {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-auto th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #1C3A6B;
    }

    .table-auto td, .table-auto th {
        padding: 0.75rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-auto tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-auto tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Sort Icon Styles */
    .sort-icon {
        display: inline-block;
        width: 16px;
        height: 16px;
        text-align: center;
        line-height: 16px;
        font-size: 12px;
        transition: transform 0.2s ease;
    }

    /* Status Badge Styles */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-badge.selesai {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-badge.proses {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.baru {
        background-color: #dbeafe;
        color: #1e40af;
    }

    /* Action Button Styles */
    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .action-button:hover {
        transform: translateY(-1px);
    }

    .action-button.view {
        color: #3b82f6;
    }

    .action-button.edit {
        color: #f59e0b;
    }

    .action-button.delete {
        color: #ef4444;
    }

    /* Responsive Table */
    @media (max-width: 768px) {
        .table-auto {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    /* Date Input Styles */
    input[type="date"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: white;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        cursor: pointer;
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
    }

    /* Filter Section Styles */
    .filter-section {
        transition: all 0.3s ease;
    }

    .filter-section:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Button Hover Effects */
    button {
        transition: all 0.2s ease;
    }

    button:hover {
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
    }
</style>
@endsection