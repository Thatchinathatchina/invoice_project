@extends('layouts.admin')
@section('title', 'Budget vs Expense Report')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Budget Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Period</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Budgeted</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Spent</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Remaining</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Usage</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($budgets as $b)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $b['name'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $b['start_date'] }} – {{ $b['end_date'] }}</td>
                        <td class="px-6 py-4 text-sm text-right font-medium">₹{{ number_format($b['budgeted'], 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-medium text-red-600">₹{{ number_format($b['spent'], 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-medium {{ $b['remaining'] >= 0 ? 'text-green-600' : 'text-red-600' }}">₹{{ number_format($b['remaining'], 2) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2"><div class="h-2 rounded-full {{ $b['usage_percent'] > 90 ? 'bg-red-500' : ($b['usage_percent'] > 70 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ min($b['usage_percent'], 100) }}%"></div></div>
                                <span class="text-xs font-medium text-gray-500">{{ $b['usage_percent'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No budgets found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
