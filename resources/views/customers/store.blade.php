<x-modal name="add-customer" :show="$errors->storeCustomer->any()">
    <div class="bg-white px-6 pt-6 pb-6 relative">
        <div class="absolute top-4 right-4">
            <x-buttons.close-icon @click="$dispatch('close-modal', 'add-customer')" />
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Add Customer</h3>
            <p class="text-sm text-gray-500 mt-1">Define a new customer profile.</p>
        </div>

        @if($errors->storeCustomer->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->storeCustomer->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.store') }}" method="POST" novalidate>
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Acme Corp" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2 @error('name', 'storeCustomer') border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50 @enderror">
                    @error('name', 'storeCustomer')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. contact@acme.com" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2 @error('email', 'storeCustomer') border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50 @enderror">
                    @error('email', 'storeCustomer')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 9876543210" required maxlength="15" pattern="[0-9]{1,15}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2 @error('phone', 'storeCustomer') border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50 @enderror">
                    @error('phone', 'storeCustomer')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tax/GST Number</label>
                    <input type="text" name="tax_number" value="{{ old('tax_number') }}" placeholder="e.g. 29ABCDE1234F1Z5" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Chennai" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2">
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                <textarea name="address" rows="2" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2 @error('address', 'storeCustomer') border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50 @enderror">{{ old('address') }}</textarea>
                @error('address', 'storeCustomer')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-center space-x-3 pt-4 border-t border-gray-100">
                <x-buttons.cancel @click="$dispatch('close-modal', 'add-customer')" class="w-1/2">Cancel</x-buttons.cancel>
                <x-buttons.submit class="w-1/2">SAVE</x-buttons.submit>
            </div>
        </form>
    </div>
</x-modal>
