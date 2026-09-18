<x-modal name="edit-customer" :show="$errors->updateCustomer->any()">
    <div class="bg-white px-6 pt-6 pb-6 relative">
        <div class="absolute top-4 right-4">
            <x-buttons.close-icon @click="$dispatch('close-modal', 'edit-customer')" />
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Edit Customer</h3>
            <p class="text-sm text-gray-500 mt-1">Update existing customer details.</p>
        </div>

        @if($errors->updateCustomer->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->updateCustomer->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form :action="updateAction" method="POST" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" name="id" x-model="form.id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="form.name" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2 @error('name', 'updateCustomer') border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50 @enderror">
                    @error('name', 'updateCustomer')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" x-model="form.email" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2 @error('email', 'updateCustomer') border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50 @enderror">
                    @error('email', 'updateCustomer')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" x-model="form.phone" placeholder="e.g. 9876543210" required maxlength="15" pattern="[0-9]{1,15}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2 @error('phone', 'updateCustomer') border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50 @enderror">
                    @error('phone', 'updateCustomer')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tax/GST Number</label>
                    <input type="text" name="tax_number" x-model="form.tax_number" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" x-model="form.city" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" x-model="form.status" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                <textarea name="address" x-model="form.address" rows="2" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2 @error('address', 'updateCustomer') border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50 @enderror"></textarea>
                @error('address', 'updateCustomer')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-center space-x-3 pt-4 border-t border-gray-100">
                <x-buttons.cancel @click="$dispatch('close-modal', 'edit-customer')" class="w-1/2">Cancel</x-buttons.cancel>
                <x-buttons.submit class="w-1/2">SAVE</x-buttons.submit>
            </div>
        </form>
    </div>
</x-modal>
