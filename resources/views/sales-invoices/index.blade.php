@extends('layouts.admin')
@section('title', 'Sales Invoices')

@section('content')
<div x-data="invoiceManager()">
    <form method="GET" action="{{ route('sales-invoices.index') }}">
        <x-filters.bar>
            <x-filters.search placeholder="Search invoice number, customer..." />
            <x-filters.select name="status">
                <option value="">All Status</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === (string)$value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </x-filters.select>
            <x-slot:actions>
                <button type="button" @click="$dispatch('open-store')" class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm" title="New Invoice">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </x-slot:actions>
        </x-filters.bar>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice #</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Due Date</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-emerald-600"><a href="{{ route('sales-invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->customer->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->invoice_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->due_date?->format('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800 text-right">₹{{ number_format($invoice->total, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                {{ $invoice->status?->color() === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $invoice->status?->color() === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $invoice->status?->color() === 'red' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $invoice->status?->color() === 'gray' ? 'bg-gray-100 text-gray-700' : '' }}
                            ">{{ $invoice->status?->label() ?? 'Draft' }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end items-center gap-3">
                                <x-actions.view @click="viewInvoice({{ $invoice->load('salesInvoiceItems', 'customer')->toJson() }})" />
                                <x-actions.edit @click="editInvoice({{ $invoice->load('salesInvoiceItems')->toJson() }})" />
                                <form action="{{ route('sales-invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <x-actions.delete type="submit" />
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No sales invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $invoices->links() }}</div>
    </div>

    @include('sales-invoices.show')
    @include('sales-invoices.store')
    @include('sales-invoices.update')
</div>

<script>
    const productPrices = { @foreach($products as $p){{ $p->id }}: {{ $p->price }},@endforeach };
    
    function invoiceManager() {
        return {
            updateAction: '',
            
            // Store Modal State
            currency_code: 'INR',
            exchange_rate: 1,
            discount: 0,
            items: [{ product_id: '', description: '', quantity: 1, unit_price: 0, tax_amount: 0, discount: 0 }],
            subtotal: 0,
            taxTotal: 0,
            grandTotal: 0,
            
            async fetchExchangeRate(isUpdate = false) {
                const currency = isUpdate ? this.updateForm.currency_code : this.currency_code;
                if (currency === 'INR') {
                    if (isUpdate) this.updateForm.exchange_rate = 1;
                    else this.exchange_rate = 1;
                    return;
                }
                
                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!token) return;
                    
                    const response = await fetch('/currency/convert', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({ amount: 1, from: currency, to: 'INR' })
                    });
                    const data = await response.json();
                    if (data.success && data.rate) {
                        if (isUpdate) this.updateForm.exchange_rate = data.rate;
                        else this.exchange_rate = data.rate;
                    }
                } catch (e) {
                    console.log('Could not automatically fetch exchange rate for ' + currency + '. Please enter manually.');
                }
            },

            // Update Modal State
            updateForm: {
                id: null,
                invoice_number: '',
                customer_id: '',
                status: '',
                currency_code: '',
                exchange_rate: 1,
                invoice_date: '',
                due_date: '',
                notes: '',
                discount: 0,
                items: [],
                subtotal: 0,
                taxTotal: 0,
                grandTotal: 0
            },

            // View Modal State
            viewForm: {
                invoice_number: '',
                invoice_date_formatted: '',
                customer_name: '',
                customer_email: '',
                customer_phone: '',
                status: '',
                currency_code: 'INR',
                exchange_rate: 1,
                subtotal: 0,
                tax_amount: 0,
                total_amount: 0,
                items: []
            },

            init() {
                window.addEventListener('open-store', () => {
                    this.$dispatch('open-modal', 'add-invoice');
                });
                
                @if($errors->storeInvoice->any())
                    this.$dispatch('open-modal', 'add-invoice');
                @endif
                
                @if($errors->updateInvoice->any())
                    this.$dispatch('open-modal', 'edit-invoice');
                @endif
                
                @if($errors->storePayment->any())
                    this.$dispatch('open-modal', 'record-payment');
                @endif
            },

            // Create methods
            addItem() { this.items.push({ product_id: '', description: '', quantity: 1, unit_price: 0, tax_amount: 0, discount: 0 }); },
            removeItem(i) { this.items.splice(i, 1); this.calculateTotals(); },
            onProductSelect(i) { this.items[i].unit_price = productPrices[this.items[i].product_id] || 0; this.calculateTotals(); },
            calculateTotals() { 
                this.subtotal = this.items.reduce((s, i) => s + this.lineTotal(i), 0); 
                this.taxTotal = this.items.reduce((s, i) => s + (parseFloat(i.tax_amount) || 0), 0); 
                this.grandTotal = (this.subtotal + this.taxTotal) - parseFloat(this.discount || 0); 
            },

            // Update methods
            editInvoice(invoice) {
                this.updateAction = `/sales-invoices/${invoice.id}`;
                this.updateForm.id = invoice.id;
                this.updateForm.invoice_number = invoice.invoice_number;
                this.updateForm.customer_id = invoice.customer_id;
                this.updateForm.status = invoice.status;
                this.updateForm.currency_code = invoice.currency_code || 'INR';
                this.updateForm.exchange_rate = invoice.exchange_rate || 1;
                this.updateForm.invoice_date = invoice.invoice_date ? invoice.invoice_date.split('T')[0] : '';
                this.updateForm.due_date = invoice.due_date ? invoice.due_date.split('T')[0] : '';
                this.updateForm.notes = invoice.notes;
                this.updateForm.discount = parseFloat(invoice.discount) || 0;
                
                this.updateForm.items = invoice.sales_invoice_items.map(item => ({
                    product_id: item.product_id,
                    description: item.description || '',
                    quantity: parseFloat(item.quantity) || 1,
                    unit_price: parseFloat(item.unit_price) || 0,
                    tax_amount: parseFloat(item.tax_amount) || 0,
                    discount: parseFloat(item.discount) || 0
                }));
                
                this.calculateUpdateTotals();
                this.$dispatch('open-modal', 'edit-invoice');
            },
            addUpdateItem() { this.updateForm.items.push({ product_id: '', description: '', quantity: 1, unit_price: 0, tax_amount: 0, discount: 0 }); },
            removeUpdateItem(i) { this.updateForm.items.splice(i, 1); this.calculateUpdateTotals(); },
            onUpdateProductSelect(i) { this.updateForm.items[i].unit_price = productPrices[this.updateForm.items[i].product_id] || 0; this.calculateUpdateTotals(); },
            calculateUpdateTotals() { 
                this.updateForm.subtotal = this.updateForm.items.reduce((s, i) => s + this.lineTotal(i), 0); 
                this.updateForm.taxTotal = this.updateForm.items.reduce((s, i) => s + (parseFloat(i.tax_amount) || 0), 0); 
                this.updateForm.grandTotal = (this.updateForm.subtotal + this.updateForm.taxTotal) - parseFloat(this.updateForm.discount || 0); 
            },

            // View method
            viewInvoice(invoice) {
                this.viewForm.invoice_number = invoice.invoice_number;
                this.viewForm.invoice_date_formatted = invoice.invoice_date ? invoice.invoice_date.split('T')[0] : '';
                this.viewForm.customer_name = invoice.customer ? invoice.customer.name : 'N/A';
                this.viewForm.customer_email = invoice.customer ? invoice.customer.email : '';
                this.viewForm.customer_phone = invoice.customer ? invoice.customer.phone : '';
                this.viewForm.status = invoice.status ? invoice.status : 'Pending';
                this.viewForm.currency_code = invoice.currency_code || 'INR';
                this.viewForm.exchange_rate = invoice.exchange_rate || 1;
                this.viewForm.subtotal = invoice.subtotal || 0;
                this.viewForm.tax_amount = invoice.tax || 0; // Note: Sales invoices use 'tax' instead of 'tax_amount' on main record
                this.viewForm.total_amount = invoice.total || 0; // Sales invoices use 'total' instead of 'total_amount'
                
                this.viewForm.items = invoice.sales_invoice_items.map(item => {
                    const product = Object.entries(productPrices).find(([id]) => id == item.product_id);
                    return {
                        name: item.description || 'Product ' + item.product_id,
                        quantity: parseFloat(item.quantity) || 1,
                        unit_price: parseFloat(item.unit_price) || 0,
                        total: (parseFloat(item.quantity) * parseFloat(item.unit_price)) - (parseFloat(item.discount) || 0)
                    };
                });
                
                this.$dispatch('open-modal', 'view-invoice');
            },

            // Payment Modal State
            paymentForm: {
                payable_id: null,
                payable_type: '',
                invoiceNumber: '',
                amount: 0,
                payment_date: new Date().toISOString().split('T')[0],
                payment_method: 1,
                reference_number: '',
                notes: ''
            },
            
            payInvoice(invoice, type) {
                this.paymentForm.payable_id = invoice.id;
                this.paymentForm.payable_type = type;
                this.paymentForm.invoiceNumber = invoice.invoice_number;
                this.paymentForm.amount = invoice.total - (invoice.paid_amount || 0);
                if (this.paymentForm.amount <= 0) this.paymentForm.amount = invoice.total;
                this.paymentForm.payment_date = new Date().toISOString().split('T')[0];
                this.paymentForm.payment_method = 1;
                this.paymentForm.reference_number = '';
                this.paymentForm.notes = '';
                this.$dispatch('open-modal', 'record-payment');
            },

            // Common
            lineTotal(item) { return (parseFloat(item.quantity) * parseFloat(item.unit_price)) - (parseFloat(item.discount) || 0); }
        }
    }
</script>
@endsection
