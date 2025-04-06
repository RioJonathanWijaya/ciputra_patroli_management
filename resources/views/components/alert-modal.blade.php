@if(session('success') || session('error'))
<div id="alertModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl space-y-4 animate-fade-in">
        <div class="flex items-center gap-3">
            @if(session('success'))
            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <h2 class="text-lg font-semibold text-green-700">Success</h2>
            @elseif(session('error'))
            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <h2 class="text-lg font-semibold text-red-700">Error</h2>
            @endif
        </div>

        <p class="text-gray-700 text-sm">
            {{ session('success') ?? session('error') }}
        </p>

        <div class="text-right">
            <button onclick="closeAlertModal()" class="px-4 py-2 bg-[#1C3A6B] hover:bg-[#172f5a] text-black rounded-xl text-sm font-semibold">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function closeAlertModal() {
        document.getElementById('alertModal').style.display = 'none';
    }

    document.addEventListener("DOMContentLoaded", function () {
        let modal = document.getElementById('alertModal');
        if (modal) {
            modal.style.display = 'flex';
        }
    });
</script>
@endif
