<x-modal name="add-budget" :show="$errors->storeBudget->any()">
    <div class="bg-white px-6 pt-6 pb-6 relative">
        <div class="absolute top-4 right-4">
            <x-buttons.close-icon @click="$dispatch('close-modal', 'add-budget')" />
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Add Budget</h3>
            <p class="text-sm text-gray-500 mt-1">Create a new budget tracking limit.</p>
        </div>

        @if($errors->storeBudget->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->storeBudget->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('budgets.store') }}" method="POST">
            @csrf
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₹) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2">
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-center space-x-3 pt-6 mt-6 border-t border-gray-100">
                <x-buttons.cancel @click="$dispatch('close-modal', 'add-budget')" class="w-1/2">Cancel</x-buttons.cancel>
                <x-buttons.submit class="w-1/2">SAVE BUDGET</x-buttons.submit>
            </div>
        </form>
    </div>
</x-modal>
