@extends('layouts.admin')
@section('title', 'Profit & Loss Report')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('reports.profit-loss') }}" class="flex items-end space-x-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">From</label><input type="date" name="start_date" value="{{ $startDate }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">To</label><input type="date" name="end_date" value="{{ $endDate }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">Filter</button>
        </form>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div class="flex justify-between items-center py-3 border-b border-gray-100"><span class="text-gray-600 font-medium">Sales Revenue</span><span class="text-lg font-bold text-green-600">₹{{ number_format($salesRevenue, 2) }}</span></div>
        <div class="flex justify-between items-center py-3 border-b border-gray-100"><span class="text-gray-600 font-medium">Purchase Cost</span><span class="text-lg font-bold text-red-600">-₹{{ number_format($purchaseCost, 2) }}</span></div>
        <div class="flex justify-between items-center py-3 border-b border-gray-200 bg-gray-50 px-3 rounded-lg"><span class="text-gray-800 font-semibold">Gross Profit</span><span class="text-lg font-bold {{ $grossProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">₹{{ number_format($grossProfit, 2) }}</span></div>
        <div class="flex justify-between items-center py-3 border-b border-gray-100"><span class="text-gray-600 font-medium">Expenses</span><span class="text-lg font-bold text-orange-600">-₹{{ number_format($expenses, 2) }}</span></div>
        <div class="flex justify-between items-center py-4 bg-emerald-50 px-4 rounded-xl"><span class="text-emerald-800 font-bold text-lg">Net Profit</span><span class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">₹{{ number_format($netProfit, 2) }}</span></div>
    </div>
</div>
@endsection
