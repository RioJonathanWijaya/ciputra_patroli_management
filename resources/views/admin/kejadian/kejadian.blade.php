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

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Manajemen Laporan Kejadian</h1>
        <p class="text-gray-600 mt-1">Daftar laporan kejadian yang tercatat dalam sistem</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <x-filter-search
            searchPlaceholder="Cari laporan..."
            :filterOptions="[
                'all' => 'Semua Status',
                'baru' => 'Status: Baru',
                'proses' => 'Status: Proses',
                'selesai' => 'Status: Selesai'
            ]"
            filterLabel="Filter Status"
            filterButton="true"
        />
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-xl">
            <table class="min-w-full table-auto divide-y divide-gray-200 text-sm text-gray-700">
                <thead class="bg-[#1C3A6B] text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" data-sort="no">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" data-sort="nama">Nama Kejadian</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" data-sort="tanggal">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" data-sort="lokasi">Lokasi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" data-sort="tipe">Tipe</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" data-sort="waktu">Waktu Laporan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider" data-sort="status">Status</th>
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

    <div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="modalContent bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Detail Laporan Kejadian
                        </h2>
                        <p class="text-gray-500 mt-1">Informasi lengkap tentang laporan kejadian</p>
                    </div>
                    <button id="closeDetailModal" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Dasar
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Nama Kejadian</p>
                                <p id="detail-nama" class="font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Kejadian</p>
                                <p id="detail-tanggal" class="font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p id="detail-lokasi" class="font-medium text-gray-800">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Laporan
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Tipe Kejadian</p>
                                <p id="detail-tipe" class="font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Waktu Laporan</p>
                                <p id="detail-waktu" class="font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Petugas</p>
                                <p id="detail-satpam" class="font-medium text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status & Keterangan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p id="detail-status" class="font-medium text-gray-800">-</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Keterangan</p>
                            <p id="detail-keterangan" class="font-medium text-gray-800">-</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="printDetail()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak
                    </button>
                    <button type="button" id="closeDetailModalBtn"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Hapus Laporan Kejadian</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus laporan ini? Data yang sudah dihapus tidak dapat dikembalikan.</p>
                        </div>
                    </div>
                </div>
                <form id="deleteForm" method="POST" class="mt-5 flex justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="hideDeleteModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>

<script>
   document.addEventListener("DOMContentLoaded", function() {
    // Initialize Firebase
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

    const searchInput = document.querySelector('.search-input');
    const filterSelect = document.querySelector('.filter-select');
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
    }

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    function filterKejadian() {
        const rows = kejadianBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const status = row.getAttribute('data-status').toLowerCase();
            const rowText = row.textContent.toLowerCase();
            
            const filterMatch = currentFilter === 'all' || status === currentFilter;
            
            const searchMatch = currentSearch === '' || 
                rowText.includes(currentSearch);
            
            row.style.display = (filterMatch && searchMatch) ? '' : 'none';
        });
    }

    function fetchKejadianData() {
        fetch("{{ route('admin.kejadian.index') }}")
            .then(response => response.json())
            .then(responseData => {
                const kejadianList = responseData.data;

                if (Array.isArray(kejadianList)) {
                    kejadianList.forEach((kejadian, index) => {
                        addKejadianToTable(kejadian, index + 1);
                        renderedIds.add(kejadian.id);
                    });
                } else {
                    console.warn("Unexpected response format:", responseData);
                }
            })
            .catch(error => {
                console.error("Error fetching kejadian data:", error);
                showErrorToast("Gagal memuat data kejadian");
            });
    }

    function listenForRealtimeUpdates() {
        database.ref('kejadian').on('child_added', (snapshot) => {
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

                    addKejadianToTable(kejadianObj);
                    renderedIds.add(kejadianId);
                    showSuccessToast("Laporan baru diterima");
                });
        });
    }

    function addKejadianToTable(kejadian, number = null) {
        const rowNumber = number ?? kejadianBody.children.length + 1;
        const status = kejadian.status ? kejadian.status.toLowerCase() : '';

        const newRow = document.createElement("tr");
        newRow.className = "hover:bg-gray-50 transition";
        newRow.setAttribute('data-status', status);

        newRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${rowNumber}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${kejadian.nama_kejadian || '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${kejadian.tanggal_kejadian ? new Date(kejadian.tanggal_kejadian).toLocaleDateString() : '-'}</div>
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
                <div class="text-sm text-gray-900">${kejadian.waktu_laporan ? new Date(kejadian.waktu_laporan).toLocaleString() : '-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="${getStatusClass(kejadian.status)} px-2 py-1 text-xs font-semibold rounded-full">
                    ${kejadian.status || 'Tidak diketahui'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end items-center gap-3">
                    <button onclick="showDetailModal('${kejadian.id}')" class="text-blue-600 hover:text-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                    <a href="/admin/kejadian/${kejadian.id}/edit" class="text-yellow-600 hover:text-yellow-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <button onclick="showDeleteModal('${kejadian.id}')" class="text-red-600 hover:text-red-900">
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

    function hideModal(modal, content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function showModal() {
        const modal = document.getElementById('detailModal');
        const content = modal.querySelector('.modalContent');

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    async function showDetailModal(id) {
        try {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
            <div class="p-6 flex flex-col items-center justify-center h-64">
                <svg class="animate-spin h-8 w-8 text-[#1C3A6B]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-gray-600">Memuat data kejadian...</p>
            </div>
        `;

            showModal();

            const response = await fetch(`/admin/kejadian/${id}`);
            console.log(response);

            if (!response.ok) {
                throw new Error('Gagal memuat data kejadian');
            }

            const kejadianData = await response.json();

            

            modalContent.innerHTML = `
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1C3A6B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Detail Laporan Kejadian
                        </h2>
                        <p class="text-gray-500 mt-1">ID: ${id}</p>
                    </div>
                    <button id="closeDetailModal" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Dasar
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Nama Kejadian</p>
                                <p id="detail-nama" class="font-medium text-gray-800">${kejadianData.data.nama_kejadian || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Kejadian</p>
                                <p id="detail-tanggal" class="font-medium text-gray-800">${kejadianData.data.tanggal_kejadian ? new Date(kejadianData.data.tanggal_kejadian).toLocaleDateString() : '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p id="detail-lokasi" class="font-medium text-gray-800">${kejadianData.data.lokasi_kejadian || '-'}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Laporan
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Tipe Kejadian</p>
                                <p id="detail-tipe" class="font-medium text-gray-800">${kejadianData.data.tipe_kejadian || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Waktu Laporan</p>
                                <p id="detail-waktu" class="font-medium text-gray-800">${kejadianData.data.waktu_laporan ? new Date(kejadianData.data.waktu_laporan).toLocaleString() : '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Petugas</p>
                                <p id="detail-satpam" class="font-medium text-gray-800">${kejadianData.data.satpam_nama || '-'}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status & Keterangan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p id="detail-status" class="font-medium text-gray-800">${kejadianData.data.status || '-'}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Keterangan</p>
                            <p id="detail-keterangan" class="font-medium text-gray-800">${kejadianData.data.keterangan || '-'}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Foto Bukti
                    </h3>
                    ${
                        kejadianData.foto_bukti_kejadian && kejadianData.foto_bukti_kejadian.length > 0
                        ? `<div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            ${kejadianData.foto_bukti_kejadian.map(foto => `
                                <a href="${foto}" target="_blank" class="group">
                                    <img src="${foto}" alt="Foto bukti kejadian" class="w-full h-24 object-cover rounded-lg group-hover:opacity-75 transition">
                                </a>
                            `).join('')}
                        </div>`
                        : `<p class="text-gray-600 text-sm italic">Tidak ada foto bukti kejadian.</p>`
                    }
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1119 12.001M12 7v5l3 3" />
                        </svg>
                        Informasi Korban
                    </h3>
                    ${
                        kejadianData.korban && kejadianData.korban.length > 0
                        ? `<ul class="list-disc list-inside text-gray-800 space-y-1">
                            ${kejadianData.korban.map(k => `<li>${k}</li>`).join('')}
                        </ul>`
                        : `<p class="text-gray-600 text-sm italic">Tidak ada data korban.</p>`
                    }
                </div>


                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Progress Tindakan
                    </h3>
                    ${
                        kejadianData.tindakan && kejadianData.tindakan.length > 0
                        ? `<div class="space-y-4">
                            ${kejadianData.tindakan.map(tindakan => `
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div class="w-0.5 h-full bg-gray-200"></div>
                                    </div>
                                    <div class="flex-1 pb-4">
                                        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                                            <p class="text-gray-800">${tindakan.tindakan || 'Tidak ada deskripsi tindakan'}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                ${tindakan.waktu_tindakan ? new Date(tindakan.waktu_tindakan).toLocaleString() : 'Waktu tidak diketahui'}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>`
                        : `<div class="text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 mt-2">Belum ada tindakan yang dicatat</p>
                        </div>`
                    }
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12h.01M12 12h.01M9 12h.01M3 12h.01M21 12h.01" />
                        </svg>
                        Tindakan Manajemen
                    </h3>
                    <form id="tindakanForm" method="POST" action="{{ route('admin.kejadian.saveTindakan') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="kejadian_id" value="${id}">
                        
                        <textarea name="tindakan" id="tindakanInput" class="w-full p-3 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Tuliskan tindakan..."></textarea>
                        
                        <button type="submit" class="bg-[#1C3A6B] hover:bg-[#2a4f8a] text-white font-medium px-4 py-2 rounded-lg">
                            Simpan Tindakan
                        </button>
                    </form>
                </div>
            </div>
        `;

        const modal = document.getElementById('detailModal');
const content = modal.querySelector('.modalContent');


document.getElementById('closeDetailModal').addEventListener('click', () => {
    hideModal(modal, content);
});
        } catch (error) {
            console.error('Error:', error);
            modalContent.innerHTML = `
                <div class="p-6">
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-800 mt-4">Gagal memuat data</h3>
                        <p class="text-gray-600 mt-1">${error.message}</p>
                        <button id="closeErrorModal" class="mt-4 bg-[#1C3A6B] hover:bg-[#2a4f8a] text-white font-medium px-4 py-2 rounded-lg">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
            const modal = document.getElementById('detailModal');
const content = modal.querySelector('.modalContent');


document.getElementById('closeErrorModal').addEventListener('click', () => {
    hideModal(modal, content);
});
        }
    }

    function showDeleteModal(id) {
        const form = document.getElementById('deleteForm');
        form.setAttribute('action', `/admin/kejadian/delete/${id}`);

        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
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
</style>
@endsection