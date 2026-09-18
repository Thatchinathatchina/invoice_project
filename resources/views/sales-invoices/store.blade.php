<x-modal name="add-invoice" :show="$errors->storeInvoice->any()" maxWidth="5xl">
    <div class="bg-white px-6 pt-6 pb-6 relative">
        <div class="absolute top-4 right-4">
            <x-buttons.close-icon @click="$dispatch('close-modal', 'add-invoice')" />
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Add Sales Invoice</h3>
            <p class="text-sm text-gray-500 mt-1">Create a new sales invoice.</p>
        </div>

        @if($errors->storeInvoice->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->storeInvoice->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sales-invoices.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Invoice #</label><input type="text" value="{{ $invoiceNumber }}" disabled class="w-full rounded-lg bg-gray-100 border-gray-300 text-gray-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>
                        <select name="customer_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select Customer</option>
                            @foreach($customers as $c)<option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                            @foreach($statuses as $val => $lbl)<option value="{{ $val }}" {{ old('status', 1) == $val ? 'selected' : '' }}>{{ $lbl }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Invoice Date <span class="text-red-500">*</span></label><input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label><input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency <span class="text-red-500">*</span></label>
                        <select name="currency_code" x-model="currency_code" @change="fetchExchangeRate(false)" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                            @foreach($currencies as $code => $currency)
                                <option value="{{ $code }}">{{ $code }} ({{ $currency['symbol'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Exchange Rate (to INR) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.000001" name="exchange_rate" x-model="exchange_rate" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Discount (₹)</label><input type="number" step="0.01" name="discount" x-model="discount" @input="calculateTotals()" value="{{ old('discount', 0) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-800 mb-2 uppercase tracking-wider">Invoice Items</h3>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase w-1/4">Product</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase w-20">Qty</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase w-28">Price</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase w-24">Tax</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase w-24">Discount</th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase w-28">Total</th>
                                <th class="px-3 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="p-2">
                                        <select :name="'items['+index+'][product_id]'" x-model="item.product_id" @change="onProductSelect(index)" required class="w-full text-sm rounded-md border-gray-300 py-1.5 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                            <option value="">Select</option>
                                            @foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                                        </select>
                                    </td>
                                    <td class="p-2"><input type="text" :name="'items['+index+'][description]'" x-model="item.description" class="w-full text-sm rounded-md border-gray-300 py-1.5 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></td>
                                    <td class="p-2"><input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" @input="calculateTotals()" min="1" required class="w-full text-sm rounded-md border-gray-300 py-1.5 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></td>
                                    <td class="p-2"><input type="number" step="0.01" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" @input="calculateTotals()" required class="w-full text-sm rounded-md border-gray-300 py-1.5 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></td>
                                    <td class="p-2"><input type="number" step="0.01" :name="'items['+index+'][tax_amount]'" x-model.number="item.tax_amount" @input="calculateTotals()" class="w-full text-sm rounded-md border-gray-300 py-1.5 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></td>
                                    <td class="p-2"><input type="number" step="0.01" :name="'items['+index+'][discount]'" x-model.number="item.discount" @input="calculateTotals()" class="w-full text-sm rounded-md border-gray-300 py-1.5 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></td>
                                    <td class="p-2 text-right text-sm font-medium text-gray-800" x-text="'₹ ' + lineTotal(item).toFixed(2)"></td>
                                    <td class="p-2 text-center"><button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-gray-800 hover:text-black">✕</button></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <button type="button" @click="addItem()" class="mt-3 inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-md hover:bg-emerald-100 transition">+ Add Item</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 rounded-xl border border-gray-200 p-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Notes</label><textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('notes') }}</textarea></div>
                <div class="space-y-2 text-sm bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal:</span><span class="font-medium" x-text="'₹ ' + subtotal.toFixed(2)"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Tax:</span><span class="font-medium" x-text="'₹ ' + taxTotal.toFixed(2)"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Discount:</span><span class="font-medium text-red-500" x-text="'-₹ ' + parseFloat(discount || 0).toFixed(2)"></span></div>
                    <div class="flex justify-between border-t border-gray-200 pt-2"><span class="text-gray-800 font-semibold">Grand Total (INR):</span><span class="text-lg font-bold text-emerald-600" x-text="'₹ ' + grandTotal.toFixed(2)"></span></div>
                    <template x-if="currency_code !== 'INR'">
                        <div class="flex justify-between mt-2 pt-2 border-t border-gray-200 text-gray-500 text-xs"><span x-text="'Amount in ' + currency_code + ':'"></span> <span x-text="currency_code + ' ' + (grandTotal / exchange_rate).toFixed(2)"></span></div>
                    </template>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a @click="$dispatch('close-modal', 'add-invoice')" class="cursor-pointer px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 text-sm text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-sm">Save Invoice</button>
            </div>
        </form>
    </div>
</x-modal>
