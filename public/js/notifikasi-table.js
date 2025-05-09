class NotifikasiTable extends TableFilter {
    constructor() {
        super({
            tableBodyId: 'notifikasi-table-body',
            searchInputClass: 'search-notifikasi',
            filterSelectClass: 'filter-notifikasi',
            statusAttribute: 'data-status'
        });
    }

    generateRowHtml(data, number) {
        const statusClass = this.getStatusClass(data.status);
        return `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${number}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${data.judul}</td>
            <td class="px-6 py-4 text-sm text-gray-900">${data.deskripsi}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${data.tanggal}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                    ${data.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button onclick="openDetailModal('${data.id}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</button>
                <button onclick="markAsRead('${data.id}')" class="text-green-600 hover:text-green-900">Tandai Dibaca</button>
            </td>
        `;
    }

    getStatusClass(status) {
        switch (status.toLowerCase()) {
            case 'unread':
                return 'bg-red-100 text-red-800';
            case 'read':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}

// Initialize the table when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    window.notifikasiTable = new NotifikasiTable();
}); 