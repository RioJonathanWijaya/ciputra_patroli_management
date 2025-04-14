export default class FilterSearch {
    constructor(options = {}) {
        const defaults = {
            tableId: null,
            searchInput: '.search-input',
            filterSelect: '.filter-select',
            searchKey: 'search',
            filterKey: 'filter',
            paginationLinks: '.pagination a',
            rows: 'tbody tr',
            rowTemplate: null,
            apiUrl: null,
            state: {
                searchQuery: '',
                filterValue: ''
            }
        };

        this.options = {...defaults, ...options};
        this.init();
    }

    init() {
        if (typeof Alpine === 'function') {
            Alpine.data('filterSearch', () => ({
                searchQuery: this.options.state.searchQuery,
                filterValue: this.options.state.filterValue,
                
                applyFilters() {
                    if (this.options.apiUrl) {
                        this.fetchData();
                    } else {
                        this.filterTable();
                    }
                },
                
                async fetchData() {
                    const params = new URLSearchParams();
                    
                    if (this.searchQuery) {
                        params.append(this.options.searchKey, this.searchQuery);
                    }
                    
                    if (this.filterValue) {
                        params.append(this.options.filterKey, this.filterValue);
                    }
                    
                    try {
                        const response = await fetch(`${this.options.apiUrl}?${params.toString()}`);
                        const data = await response.json();
                        
                        if (this.options.rowTemplate) {
                            this.renderRows(data);
                        }
                    } catch (error) {
                        console.error('Error fetching filtered data:', error);
                    }
                },
                
                filterTable() {
                    const searchTerm = this.searchQuery.toLowerCase();
                    const filterValue = this.filterValue;
                    
                    document.querySelectorAll(this.options.rows).forEach(row => {
                        const rowText = row.textContent.toLowerCase();
                        const matchesSearch = searchTerm === '' || rowText.includes(searchTerm);
                        const matchesFilter = filterValue === '' || row.dataset.filter === filterValue;
                        
                        row.style.display = matchesSearch && matchesFilter ? '' : 'none';
                    });
                },
                
                renderRows(data) {
                    const tableBody = document.querySelector(`${this.options.tableId} tbody`);
                    tableBody.innerHTML = '';
                    
                    data.forEach((item, index) => {
                        const rowHtml = this.options.rowTemplate(item, index + 1);
                        tableBody.insertAdjacentHTML('beforeend', rowHtml);
                    });
                }
            }));
        }
    }


    static initialize(options = {}) {
        return new FilterSearch(options);
    }
}