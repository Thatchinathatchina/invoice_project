@extends('layouts.admin')
@section('title', 'Profit & Loss Report')
@section('content')
<div class="space-y-6">
    <form method="GET" action="{{ route('reports.profit-loss') }}" class="flex flex-wrap md:flex-nowrap items-end gap-4 w-full">
        <div><label class="block text-sm font-medium text-gray-700 mb-1">From</label><input type="date" name="start_date" value="{{ $startDate }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">To</label><input type="date" name="end_date" value="{{ $endDate }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
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
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Account / Category</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">Sales Revenue</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 text-right">₹{{ number_format($salesRevenue, 2) }}</td>
                </tr>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">Purchase Cost</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600 text-right">-₹{{ number_format($purchaseCost, 2) }}</td>
                </tr>
                <tr class="bg-gray-50 border-t-2 border-gray-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">Gross Profit</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $grossProfit >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">₹{{ number_format($grossProfit, 2) }}</td>
                </tr>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">Operating Expenses</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-600 text-right">-₹{{ number_format($expenses, 2) }}</td>
                </tr>
            </tbody>
            <tfoot class="bg-emerald-50 border-t-2 border-emerald-200">
                <tr>
                    <td class="px-6 py-5 whitespace-nowrap text-base font-bold text-emerald-800 uppercase tracking-wider">Net Profit</td>
                    <td class="px-6 py-5 whitespace-nowrap text-xl font-bold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }} text-right">₹{{ number_format($netProfit, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
