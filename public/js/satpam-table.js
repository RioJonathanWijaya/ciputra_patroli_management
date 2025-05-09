class SatpamTable extends TableFilter {
    constructor() {
        super('satpam-table-body', 'search-satpam', 'filter-satpam', 'status');
    }

    generateRowHtml(data, number) {
        const statusClass = this.getStatusClass(data.status);
        return `
            <tr class="hover:bg-gray-50 transition-all cursor-pointer satpam-row" 
                data-url="${data.url}"
                data-nama="${data.nama}"
                data-nip="${data.nip}"
                data-email="${data.email}"
                data-shift="${data.shift}"
                data-jabatan="${data.jabatan}"
                data-telepon="${data.telepon}"
                data-lokasi="${data.lokasi}"
                data-status="${data.status}">
                <td class="px-4 py-3 whitespace-nowrap">${number}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.nip}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.nama}</td>
                <td class="px-4 py-3 whitespace-nowrap max-w-[220px] truncate">${data.email}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.shift}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.jabatan}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.telepon}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.lokasi}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full ${statusClass}">
                        ${data.status}
                    </span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <a href="${data.url}"
                        class="text-blue-600 hover:text-blue-800 font-medium transition-all duration-150">
                        Lihat Detail
                    </a>
                </td>
            </tr>
        `;
    }

    getStatusClass(status) {
        switch (status) {
            case 'aktif':
                return 'bg-green-100 text-green-800';
            case 'cuti':
                return 'bg-orange-100 text-orange-800';
            case 'tidak aktif':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}

// Initialize the table when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    new SatpamTable();
}); 