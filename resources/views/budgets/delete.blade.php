<x-modal name="delete-budget">
    <div class="bg-white px-6 pt-5 pb-6 relative text-center">
        <div class="absolute top-4 right-4">
            <x-buttons.close-icon @click="$dispatch('close-modal', 'delete-budget')" />
        </div>
        
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-5">
            <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Budget</h3>
        <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete <span class="font-semibold text-gray-800" x-text="form.name"></span>? This action cannot be undone.</p>
        
        <form :action="deleteAction" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-center space-x-3">
                <x-buttons.cancel @click="$dispatch('close-modal', 'delete-budget')" class="w-1/2">Cancel</x-buttons.cancel>
                <button type="submit" class="w-1/2 inline-flex justify-center rounded-lg border border-transparent bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors sm:w-auto">
                    Delete Budget
                </button>
            </div>
        </form>
    </div>
</x-modal>
