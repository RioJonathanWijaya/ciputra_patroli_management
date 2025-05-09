class LokasiTable extends TableFilter {
    constructor() {
        super('lokasi-table-body', 'search-lokasi', 'filter-lokasi', 'tipe');
    }

    generateRowHtml(data, number) {
        return `
            <tr data-nama="${data.nama}"
                data-alamat="${data.alamat}"
                data-deskripsi="${data.deskripsi}"
                data-tipe="${data.tipe}">
                <td class="px-6 py-4">${number}</td>
                <td class="px-6 py-4">${data.nama}</td>
                <td class="px-6 py-4">${data.alamat}</td>
                <td class="px-6 py-4">${data.deskripsi}</td>
                <td class="px-6 py-4 flex gap-3">
                    <button
                        class="text-blue-600 hover:text-blue-800 transition-all duration-200 transform hover:scale-110 relative group open-edit-modal"
                        data-id="${data.id}"
                        data-nama="${data.nama}"
                        data-alamat="${data.alamat}"
                        data-deskripsi="${data.deskripsi}">
                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                        <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">Edit</span>
                    </button>

                    <form action="${data.deleteUrl}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?')">
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
        `;
    }

    getStatusClass(tipe) {
        switch (tipe) {
            case 'cluster':
                return 'bg-blue-100 text-blue-800';
            case 'area':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}

// Initialize the table when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    new LokasiTable();
}); 