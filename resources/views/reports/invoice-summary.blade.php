@extends('layouts.admin')
@section('title', 'Invoice Summary Report')
@section('content')
<div class="space-y-6">
    <form method="GET" action="{{ route('reports.invoice-summary') }}" class="flex flex-wrap md:flex-nowrap items-end gap-4 w-full">
        <div><label class="block text-sm font-medium text-gray-700 mb-1">From</label><input type="date" name="start_date" value="{{ $startDate }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">To</label><input type="date" name="end_date" value="{{ $endDate }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Invoices</option>
                <option value="sales" {{ $type === 'sales' ? 'selected' : '' }}>Sales Only</option>
                <option value="purchase" {{ $type === 'purchase' ? 'selected' : '' }}>Purchases Only</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 h-10 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">Filter</button>
        <div class="flex-1"></div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 h-10 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Reports
        </a>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Party</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoices as $inv)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm font-medium text-gray-700">
                            {{ $inv->type_label }}
                        </td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-800">{{ $inv->invoice_number }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $inv->party_name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ $inv->invoice_date->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-medium">₹{{ number_format($inv->display_amount, 2) }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 text-xs rounded-full font-medium {{ $inv->status?->color() === 'green' ? 'bg-green-100 text-green-700' : ($inv->status?->color() === 'red' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $inv->status?->label() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">No invoices found for the selected filter.</td></tr>
                @endforelse
            </tbody>
            @if($invoices->count() > 0)            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="4" class="px-6 py-4 text-sm font-semibold text-gray-700 text-right">Total Amount:</td>
                    <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">₹{{ number_format($invoices->sum('display_amount'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    
    @if($invoices->hasPages())
    <div class="mt-4">
        {{ $invoices->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
