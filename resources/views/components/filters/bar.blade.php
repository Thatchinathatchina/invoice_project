<div class="flex flex-col md:flex-row gap-3 items-center mb-6">
    <div class="flex flex-col md:flex-row gap-3 items-center w-full md:w-auto flex-wrap">
        {{ $slot }}

        <a href="{{ request()->url() }}" class="inline-flex items-center p-2 border border-gray-300 rounded-lg shadow-sm text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition" title="Reset Filters">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </a>

        @if(isset($actions))
            {{ $actions }}
        @endif
    </div>
</div>
