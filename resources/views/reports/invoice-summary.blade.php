@extends('layouts.admin')
@section('title', 'Invoice Summary Report')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('reports.invoice-summary') }}" class="flex items-end space-x-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">From</label><input type="date" name="start_date" value="{{ $startDate }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">To</label><input type="date" name="end_date" value="{{ $endDate }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">Filter</button>
        </form>
    </div>

    {{-- Sales Invoices --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-semibold text-gray-800">Sales Invoices ({{ $salesInvoices->count() }})</h3></div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice #</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th><th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($salesInvoices as $inv)
                    <tr><td class="px-6 py-3 text-sm font-medium text-emerald-600">{{ $inv->invoice_number }}</td><td class="px-6 py-3 text-sm">{{ $inv->customer->name ?? 'N/A' }}</td><td class="px-6 py-3 text-sm text-gray-500">{{ $inv->invoice_date->format('d M Y') }}</td><td class="px-6 py-3 text-sm text-right font-medium">₹{{ number_format($inv->total, 2) }}</td><td class="px-6 py-3"><span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700">{{ $inv->status?->label() }}</span></td></tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No sales invoices in this period.</td></tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50"><tr><td colspan="3" class="px-6 py-3 text-sm font-semibold text-gray-700 text-right">Total Sales:</td><td class="px-6 py-3 text-sm text-right font-bold text-green-600">₹{{ number_format($salesInvoices->sum('total'), 2) }}</td><td></td></tr></tfoot>
        </table>
    </div>

    {{-- Purchase Invoices --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-semibold text-gray-800">Purchase Invoices ({{ $purchaseInvoices->count() }})</h3></div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice #</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Supplier</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th><th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($purchaseInvoices as $inv)
                    <tr><td class="px-6 py-3 text-sm font-medium text-emerald-600">{{ $inv->invoice_number }}</td><td class="px-6 py-3 text-sm">{{ $inv->supplier->name ?? 'N/A' }}</td><td class="px-6 py-3 text-sm text-gray-500">{{ $inv->invoice_date->format('d M Y') }}</td><td class="px-6 py-3 text-sm text-right font-medium">₹{{ number_format($inv->total_amount, 2) }}</td><td class="px-6 py-3"><span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700">{{ $inv->status?->label() }}</span></td></tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No purchase invoices in this period.</td></tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50"><tr><td colspan="3" class="px-6 py-3 text-sm font-semibold text-gray-700 text-right">Total Purchases:</td><td class="px-6 py-3 text-sm text-right font-bold text-red-600">₹{{ number_format($purchaseInvoices->sum('total_amount'), 2) }}</td><td></td></tr></tfoot>
        </table>
    </div>
</div>
@endsection
