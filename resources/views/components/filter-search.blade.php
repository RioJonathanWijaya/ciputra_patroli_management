@props([
    'searchPlaceholder' => 'Search...',
    'filterOptions' => [],
    'filterDefault' => 'all',
    'filterLabel' => 'Filter',
    'addButton' => false,
    'addButtonText' => 'Add New',
    'addButtonRoute' => '#',
    'filterButton' => false
])

<div class="px-4 py-3 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div class="relative w-full sm:w-64">
        <input 
            type="text" 
            placeholder="{{ $searchPlaceholder }}" 
            class="search-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
            x-model="searchQuery"
            @input.debounce.500ms="applyFilters()"
        >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>

    @if(count($filterOptions) > 0)
    <div class="relative">
        <select 
            class="filter-select appearance-none pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white"
            x-model="filterValue"
            @change="applyFilters()"
        >
            <option value="">{{ $filterLabel }}</option>
            @foreach($filterOptions as $value => $label)
                <option value="{{ $value }}" {{ $filterDefault == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
            </svg>
        </div>
    </div>
    @endif

    <div class="flex items-center gap-2">
        @if($addButton)
            <a href="{{ $addButtonRoute }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ $addButtonText }}
            </a>
        @endif
        
        @if($filterButton)
            <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
        @endif
    </div>
</div>