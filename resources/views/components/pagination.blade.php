@props(['items', 'perPage' => 10, 'currentPage' => 1])

@php
    $totalPages = ceil(count($items) / $perPage);
    $startItem = ($currentPage - 1) * $perPage;
    $endItem = min($startItem + $perPage, count($items));
    $paginatedItems = array_slice($items, $startItem, $perPage);
@endphp

<div class="px-4 py-3 bg-white border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center">
    <div class="text-sm text-gray-700 mb-2 sm:mb-0">
        Menampilkan <span class="font-medium">{{ $startItem + 1 }}</span> sampai <span class="font-medium">{{ $endItem }}</span> dari <span class="font-medium">{{ count($items) }}</span> hasil
    </div>
    <div class="flex space-x-1">
        <button onclick="changePage({{ $currentPage - 1 }})" 
            class="px-3 py-1 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 {{ $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ $currentPage <= 1 ? 'disabled' : '' }}>
            Previous
        </button>
        <button onclick="changePage({{ $currentPage + 1 }})"
            class="px-3 py-1 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 {{ $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ $currentPage >= $totalPages ? 'disabled' : '' }}>
            Next
        </button>
    </div>
</div>

<script>
    function changePage(page) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('page', page);
        window.location.href = currentUrl.toString();
    }
</script> 