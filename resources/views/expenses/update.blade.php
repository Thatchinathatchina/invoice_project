<x-modal name="edit-expense" :show="$errors->updateExpense->any()">
    <div class="bg-white px-6 pt-6 pb-6 relative">
        <div class="absolute top-4 right-4">
            <x-buttons.close-icon @click="$dispatch('close-modal', 'edit-expense')" />
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Edit Expense</h3>
            <p class="text-sm text-gray-500 mt-1">Update expense details.</p>
        </div>

        @if($errors->updateExpense->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->updateExpense->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form :action="updateAction" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" x-model="form.id">

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget Category <span class="text-red-500">*</span></label>
                    <select name="category_id" x-model="form.category_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Select Budget</option>
                        @foreach($budgets as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₹) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="amount" x-model="form.amount" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="expense_date" x-model="form.expense_date" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                    <select name="payment_method" x-model="form.payment_method" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach(\App\Enums\PaymentMethod::options() as $v => $l)
                            <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                    <input type="text" name="reference_number" x-model="form.reference_number" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" x-model="form.description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-center space-x-3 pt-6 mt-6 border-t border-gray-100">
                <x-buttons.cancel @click="$dispatch('close-modal', 'edit-expense')" class="w-1/2">Cancel</x-buttons.cancel>
                <x-buttons.submit class="w-1/2">UPDATE EXPENSE</x-buttons.submit>
            </div>
        </form>
    </div>
</x-modal>
