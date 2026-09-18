@extends('layouts.admin')
@section('title', $supplier->name)
@section('subtitle', 'Supplier Details')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><p class="text-xs text-gray-500 uppercase">Name</p><p class="font-medium text-gray-800">{{ $supplier->name }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Email</p><p class="font-medium text-gray-800">{{ $supplier->email ?? '-' }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Phone</p><p class="font-medium text-gray-800">{{ $supplier->phone ?? '-' }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Tax Number</p><p class="font-medium text-gray-800">{{ $supplier->tax_number ?? '-' }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Address</p><p class="font-medium text-gray-800">{{ $supplier->address ?? '-' }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Location</p><p class="font-medium text-gray-800">{{ collect([$supplier->city, $supplier->state, $supplier->country])->filter()->join(', ') ?: '-' }}</p></div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-semibold text-gray-800">Purchase Invoices</h3></div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice #</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($supplier->purchaseInvoices as $inv)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm"><a href="{{ route('purchase-invoices.show', $inv) }}" class="text-emerald-600 hover:underline">{{ $inv->invoice_number }}</a></td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $inv->invoice_date->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-sm font-medium">₹{{ number_format($inv->total_amount, 2) }}</td>
                        <td class="px-6 py-3"><span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700">{{ $inv->status?->label() }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
