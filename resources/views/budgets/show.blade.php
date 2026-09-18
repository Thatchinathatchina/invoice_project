@extends('layouts.admin')
@section('title', $budget->name)
@section('subtitle', 'Budget Details')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
            <div><p class="text-xs text-gray-500 uppercase">Budget Amount</p><p class="text-xl font-bold text-gray-800">₹{{ number_format($budget->amount, 2) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Total Spent</p><p class="text-xl font-bold text-red-600">₹{{ number_format($budget->total_spent, 2) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Remaining</p><p class="text-xl font-bold {{ $budget->remaining >= 0 ? 'text-green-600' : 'text-red-600' }}">₹{{ number_format($budget->remaining, 2) }}</p></div>
            <div><p class="text-xs text-gray-500 uppercase">Usage</p><p class="text-xl font-bold text-emerald-600">{{ $budget->usage_percent }}%</p></div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="h-3 rounded-full transition-all {{ $budget->usage_percent > 90 ? 'bg-red-500' : ($budget->usage_percent > 70 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ min($budget->usage_percent, 100) }}%"></div>
        </div>
        <p class="text-sm text-gray-500 mt-3">Period: {{ $budget->start_date->format('d M Y') }} – {{ $budget->end_date->format('d M Y') }}</p>
        @if($budget->description)<p class="text-sm text-gray-600 mt-2">{{ $budget->description }}</p>@endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-semibold text-gray-800">Expenses under this Budget</h3></div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Method</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($budget->expenses as $expense)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $expense->expense_date->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $expense->description ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm">{{ $expense->payment_method?->label() ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-right font-medium text-red-600">₹{{ number_format($expense->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">No expenses recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
