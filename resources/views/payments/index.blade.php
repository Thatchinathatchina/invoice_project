@extends('layouts.admin')
@section('title', 'Payments')
@section('subtitle', 'All payment transactions')
@section('content')
<form method="GET" action="{{ route('payments.index') }}">
    <x-filters.bar>
        <x-filters.search placeholder="Search invoice, party, or ref..." />
        
        <x-filters.select name="type">
            <option value="">All Types</option>
            <option value="sales" {{ request('type') === 'sales' ? 'selected' : '' }}>Sales Payments</option>
            <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>Purchase Payments</option>
        </x-filters.select>

        <x-filters.select name="method">
            <option value="">All Methods</option>
            @foreach($methods as $value => $label)
                <option value="{{ $value }}" {{ request('method') === (string)$value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </x-filters.select>
        
    </x-filters.bar>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50"><tr>
            <x-table.th>Payment ID</x-table.th>
            <x-table.th>Type</x-table.th>
            <x-table.th>Invoice No</x-table.th>
            <x-table.th>Party</x-table.th>
            <x-table.th>Invoice Date</x-table.th>
            <x-table.th align="right">Invoice Total</x-table.th>
            <x-table.th align="right">Payment Amount</x-table.th>
            <x-table.th align="right">Balance Amount</x-table.th>
            <x-table.th>Status</x-table.th>
            <x-table.th>Payment Date</x-table.th>
            <x-table.th>Method</x-table.th>
            <x-table.th align="right">Actions</x-table.th>
        </tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($payments as $payment)
                @php
                    $isSales = str_contains($payment->payable_type, 'SalesInvoice');
                    $invoice = $payment->payable;
                    $party = $isSales ? ($invoice->customer->name ?? 'N/A') : ($invoice->supplier->name ?? 'N/A');
                    $invoiceTotal = $isSales ? $invoice->total : $invoice->total_amount;
                    $route = $isSales ? route('sales-invoices.show', $invoice) : route('purchase-invoices.show', $invoice);
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <x-table.td>#{{ $payment->id }}</x-table.td>
                    <x-table.td><span class="px-2 py-0.5 text-xs rounded-full {{ $isSales ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">{{ $isSales ? 'Sale' : 'Purchase' }}</span></x-table.td>
                    <x-table.td primary><a href="{{ $route }}" class="hover:underline">{{ $invoice->invoice_number }}</a></x-table.td>
                    <x-table.td>{{ $party }}</x-table.td>
                    <x-table.td>{{ $invoice->invoice_date->format('d M Y') }}</x-table.td>
                    <x-table.td align="right">₹{{ number_format($invoiceTotal, 2) }}</x-table.td>
                    <x-table.td align="right" class="text-emerald-600 font-semibold">₹{{ number_format($payment->amount, 2) }}</x-table.td>
                    <x-table.td align="right">₹{{ number_format($invoice->balance_due, 2) }}</x-table.td>
                    <x-table.td><span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $invoice->status?->color() === 'green' ? 'bg-green-100 text-green-700' : ($invoice->status?->color() === 'red' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">{{ $invoice->status?->label() }}</span></x-table.td>
                    <x-table.td>{{ $payment->payment_date->format('d M Y') }}</x-table.td>
                    <x-table.td>{{ $payment->payment_method?->label() ?? '-' }}</x-table.td>
                    <x-table.td align="right">
                        <div class="flex justify-end items-center gap-3">
                            <x-actions.view href="{{ $route }}" />
                        </div>
                    </x-table.td>
                </tr>
            @empty
                <tr><td colspan="12" class="px-6 py-12 text-center text-gray-400">No payments recorded.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
</div>
@endsection
