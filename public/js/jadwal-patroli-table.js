class JadwalPatroliTable extends TableFilter {
    constructor() {
        super({
            tableBodyId: 'jadwal-patroli-table-body',
            searchInputClass: 'search-jadwal-patroli',
            filterSelectClass: 'filter-jadwal-patroli',
            statusAttribute: 'data-status'
        });
    }

    generateRowHtml(data, number) {
        const statusClass = this.getStatusClass(data.status);
        return `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${number}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${data.lokasi}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${data.satpam}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${data.hari}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${data.jam_mulai} - ${data.jam_selesai}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                    ${data.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button onclick="openDetailModal('${data.id}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</button>
                <button onclick="openDeleteModal('${data.id}')" class="text-red-600 hover:text-red-900">Hapus</button>
            </td>
        `;
    }

    getStatusClass(status) {
        switch (status.toLowerCase()) {
            case 'aktif':
                return 'bg-green-100 text-green-800';
            case 'nonaktif':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}

// Initialize the table when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    window.jadwalPatroliTable = new JadwalPatroliTable();
}); 