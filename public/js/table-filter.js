class TableFilter {
    constructor(options = {}) {
        this.options = {
            tableBodyId: 'table-body',
            searchInputClass: 'search-input',
            filterSelectClass: 'filter-select',
            statusAttribute: 'data-status',
            ...options
        };
        this.renderedIds = new Set();
        this.setupEventListeners();
    }

    setupEventListeners() {
        const searchInput = document.querySelector(`.${this.options.searchInputClass}`);
        const filterSelect = document.querySelector(`.${this.options.filterSelectClass}`);

        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(() => {
                this.filterRows();
            }, 300));
        }

        if (filterSelect) {
            filterSelect.addEventListener('change', () => {
                this.filterRows();
            });
        }
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    filterRows() {
        const searchInput = document.querySelector(`.${this.options.searchInputClass}`);
        const filterSelect = document.querySelector(`.${this.options.filterSelectClass}`);
        const rows = document.querySelectorAll(`#${this.options.tableBodyId} tr`);

        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const filterValue = filterSelect ? filterSelect.value : 'all';

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.getAttribute(this.options.statusAttribute);
            const matchesSearch = text.includes(searchTerm);
            const matchesFilter = filterValue === 'all' || status === filterValue;

            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    addRow(data) {
        const tbody = document.getElementById(this.options.tableBodyId);
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.setAttribute('data-id', data.id);
        tr.setAttribute(this.options.statusAttribute, data.status.toLowerCase());
        tr.className = 'hover:bg-gray-50 transition';
        tr.innerHTML = this.generateRowHtml(data, tbody.children.length + 1);

        tbody.appendChild(tr);
        this.renderedIds.add(data.id);
    }

    generateRowHtml(data, number) {
        // This method should be overridden by child classes
        throw new Error('generateRowHtml must be implemented by child class');
    }

    getStatusClass(status) {
        // This method should be overridden by child classes
        throw new Error('getStatusClass must be implemented by child class');
    }

    getBadgeClass(type) {
        // This method should be overridden by the specific table implementation
        return '';
    }
}

// Export the class for use in other files
window.TableFilter = TableFilter; 