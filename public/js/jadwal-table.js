class JadwalTable extends TableFilter {
    constructor() {
        super('jadwal-table-body', 'search-jadwal', 'filter-jadwal', 'shift');
    }

    generateRowHtml(data, number) {
        return `
            <tr class="hover:bg-gray-50 transition-all cursor-pointer"
                data-lokasi="${data.lokasi}"
                data-shiftpagi="${data.shiftpagi}"
                data-shiftmalam="${data.shiftmalam}"
                data-shift="${data.shift}"
                onclick="openDetailModal(this)">
                <td class="px-4 py-3 whitespace-nowrap">${number}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.lokasi}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.shiftpagi}</td>
                <td class="px-4 py-3 whitespace-nowrap">${data.shiftmalam}</td>
                <td class="px-4 py-3 whitespace-nowrap" onclick="event.stopPropagation()">
                    <div class="flex items-center gap-3">
                        <a href="${data.editUrl}"
                            class="text-blue-600 hover:text-blue-800 transition-all duration-200 transform hover:scale-110 relative group">
                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                            <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
                                Edit
                            </span>
                        </a>
                        <button type="button"
                            data-delete-url="${data.deleteUrl}"
                            onclick="showDeleteModalFromElement(this)"
                            class="text-red-600 hover:text-red-800 transition-all duration-200 transform hover:scale-110 relative group">
                            <i class="fa-solid fa-trash text-lg"></i>
                            <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
                                Delete
                            </span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    getStatusClass(status) {
        switch (status) {
            case 'pagi':
                return 'bg-yellow-100 text-yellow-800';
            case 'malam':
                return 'bg-blue-100 text-blue-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new JadwalTable();
}); 