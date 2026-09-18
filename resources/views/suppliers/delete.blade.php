<x-modal name="delete-supplier" maxWidth="md">
    <div class="bg-white px-6 pt-6 pb-6 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Delete Supplier</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to delete <span class="font-semibold text-gray-800" x-text="form.name"></span>? This action cannot be undone.</p>
        </div>

        <form :action="deleteAction" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-center space-x-3 mt-5">
                <x-buttons.cancel @click="$dispatch('close-modal', 'delete-supplier')" class="w-1/2">Cancel</x-buttons.cancel>
                <x-buttons.danger class="w-1/2">Delete</x-buttons.danger>
            </div>
        </form>
    </div>
</x-modal>
