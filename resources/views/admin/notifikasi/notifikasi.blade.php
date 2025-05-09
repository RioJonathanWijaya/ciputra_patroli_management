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
        <x-breadcrumbs :items="[
            ['label' => 'Notifikasi', 'url' => route('admin.notifikasi.notifikasi')],
        ]" />
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Notifikasi</h1>
        <p class="text-gray-600 mt-1">Daftar notifikasi kejadian dalam sistem</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <x-filter-search
            searchPlaceholder="Cari notifikasi..."
            :filterOptions="[
                'all' => 'Semua Status',
                'unread' => 'Belum Dibaca',
                'read' => 'Sudah Dibaca'
            ]"
            filterLabel="Filter Status"
            filterButton="true"
        />
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-xl">
        <table class="min-w-full table-auto divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-[#1C3A6B] text-white">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(0)">
                        No <span class="sort-icon" id="sort-icon-0">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(1)">
                        Judul <span class="sort-icon" id="sort-icon-1">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(2)">
                        Deskripsi <span class="sort-icon" id="sort-icon-2">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(3)">
                        Tanggal <span class="sort-icon" id="sort-icon-3">↕</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider cursor-pointer hover:bg-[#2a4b8a]" onclick="sortTable(4)">
                        Status <span class="sort-icon" id="sort-icon-4">↕</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="notificationsTableBody">
            </tbody>
        </table>
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
                            Detail Notifikasi
                        </h2>
                        <p class="text-gray-500 mt-1">Informasi lengkap tentang notifikasi</p>
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
                                <p class="text-sm text-gray-500">Judul</p>
                                <p id="detail-judul" class="font-medium text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal</p>
                                <p id="detail-tanggal" class="font-medium text-gray-800">${notification.timestamp ? new Date(notification.timestamp).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '-'}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p id="detail-status" class="font-medium text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Deskripsi
                    </h3>
                    <div>
                        <p id="detail-deskripsi" class="font-medium text-gray-800">-</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="markAsRead()"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Tandai Sudah Dibaca
                    </button>
                    <button type="button" id="closeDetailModalBtn"
                        class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                        Tutup
                    </button>
                </div>
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
        const kejadianRef = database.ref('kejadian');
        const notificationsRef = database.ref('notifications');
        let currentFilter = 'all';
        let currentSearch = '';

        const searchInput = document.querySelector('.search-input');
        const filterSelect = document.querySelector('.filter-select');
        const notificationsBody = document.getElementById("notificationsTableBody");

        kejadianRef.on('child_added', (snapshot) => {
            const kejadian = snapshot.val();
            const kejadianId = snapshot.key;

            if (kejadian.is_notifikasi === true) {
                const notificationData = {
                    kejadian_id: kejadianId,
                    title: kejadian.nama_kejadian || 'Kejadian Baru',
                    message: kejadian.keterangan || 'Ada kejadian baru yang perlu ditindaklanjuti',
                    timestamp: Date.now(),
                    read: false
                };

                notificationsRef.push(notificationData);
            }
        });

        kejadianRef.on('child_changed', (snapshot) => {
            const kejadian = snapshot.val();
            const kejadianId = snapshot.key;

            if (kejadian.is_notifikasi === true) {
                notificationsRef.orderByChild('kejadian_id').equalTo(kejadianId).once('value', (notifSnapshot) => {
                    if (!notifSnapshot.exists()) {
                        const notificationData = {
                            kejadian_id: kejadianId,
                            title: kejadian.nama_kejadian || 'Kejadian Diperbarui',
                            message: kejadian.keterangan || 'Ada pembaruan pada kejadian',
                            timestamp: Date.now(),
                            read: false
                        };

                        notificationsRef.push(notificationData);
                    }
                });
            }
        });

        async function removeNotification(notificationId) {
            try {
                await notificationsRef.child(notificationId).remove();
                return true;
            } catch (error) {
                console.error('Error removing notification:', error);
                return false;
            }
        }

        function loadNotifications() {
            notificationsRef.on('value', (snapshot) => {
                const notifications = snapshot.val() || {};
                updateNotificationsList(notifications);
            });
        }

        function updateNotificationsList(notifications) {
            notificationsBody.innerHTML = '';
            
            const notificationArray = Object.entries(notifications)
                .map(([id, notification]) => ({ id, ...notification }))
                .sort((a, b) => b.timestamp - a.timestamp)
                .filter(notification => {
                    if (currentFilter === 'unread' && notification.read) return false;
                    if (currentFilter === 'read' && !notification.read) return false;
                    if (currentSearch && !notification.title.toLowerCase().includes(currentSearch.toLowerCase()) &&
                        !notification.message.toLowerCase().includes(currentSearch.toLowerCase())) return false;
                    return true;
                });

            if (notificationArray.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada notifikasi
                    </td>
                `;
                notificationsBody.appendChild(emptyRow);
                return;
            }

            notificationArray.forEach((notification, index) => {
                const row = document.createElement('tr');
                row.className = `${notification.read ? 'bg-white' : 'bg-blue-50'} hover:bg-gray-50 cursor-pointer`;
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                    <td class="px-6 py-4">${notification.title}</td>
                    <td class="px-6 py-4">${notification.message}</td>
                    <td class="px-6 py-4">${formatTimestamp(notification.timestamp)}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            ${notification.read ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${notification.read ? 'Sudah Dibaca' : 'Belum Dibaca'}
                        </span>
                    </td>
                `;

                row.addEventListener('click', async () => {
                    try {
                        await removeNotification(notification.id);
                        window.location.href = `/admin/kejadian/${notification.kejadian_id}`;
                    } catch (error) {
                        console.error('Error handling notification click:', error);
                    }
                });

                notificationsBody.appendChild(row);
            });
        }

        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function markAsRead() {
            const notificationId = document.getElementById('detail-judul').getAttribute('data-id');
            if (!notificationId) return;

            $.ajax({
                url: '{{ route("api.notifications.mark-as-read") }}',
                type: 'POST',
                data: {
                    kejadian_id: notificationId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideDetailModal();
                    loadNotifications();
                },
                error: function(xhr) {
                    console.error('Error marking notification as read:', xhr);
                }
            });
        }

        function hideDetailModal() {
            const modal = document.getElementById('detailModal');
            document.getElementById('modalContent').classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        document.getElementById('closeDetailModal').addEventListener('click', hideDetailModal);
        document.getElementById('closeDetailModalBtn').addEventListener('click', hideDetailModal);

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                currentSearch = e.target.value;
                loadNotifications();
            });
        }

        if (filterSelect) {
            filterSelect.addEventListener('change', function(e) {
                currentFilter = e.target.value;
                loadNotifications();
            });
        }


        loadNotifications();
    });

    let currentSortColumn = -1;
    let sortDirection = 1;

    function sortTable(columnIndex) {
        const table = document.getElementById('notificationsTableBody');
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
            
            if (columnIndex === 3) {
                return sortDirection * (new Date(aValue) - new Date(bValue));
            }
            
            if (columnIndex === 4) {
                const statusOrder = { 'Belum Dibaca': 0, 'Sudah Dibaca': 1 };
                return sortDirection * (statusOrder[aValue] - statusOrder[bValue]);
            }
            
            return sortDirection * aValue.localeCompare(bValue);
        });
        
        rows.forEach(row => table.appendChild(row));
    }
</script>
@endsection 