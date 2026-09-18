@extends('layouts.admin')
@section('title', 'Expenses')
@section('subtitle', 'Track and manage expenses')

@section('content')
<div x-data="expenseManager()">
    <form method="GET" action="{{ route('expenses.index') }}" class="ajax-filter">
        <x-filters.bar>
            <x-filters.search placeholder="Search expenses..." />
            <x-filters.select name="budget_id">
                <option value="">All Budgets</option>
                @foreach($budgets ?? [] as $budget)
                    <option value="{{ $budget->id }}" {{ request('budget_id') == $budget->id ? 'selected' : '' }}>{{ $budget->name }}</option>
                @endforeach
            </x-filters.select>
            <x-filters.select name="payment_method">
                <option value="">Payment Method</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
            </x-filters.select>
            <x-slot:actions>
                <button type="button" @click="$dispatch('open-store')" class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm" title="Add Expense">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </x-slot:actions>
        </x-filters.bar>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <x-table.th>S.NO</x-table.th>
                <x-table.th>Date</x-table.th>
                <x-table.th>Budget</x-table.th>
                <x-table.th>Description</x-table.th>
                <x-table.th>Method</x-table.th>
                <x-table.th align="right">Amount</x-table.th>
                <x-table.th align="right">Actions</x-table.th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($expenses as $expense)
                    <tr class="hover:bg-gray-50 transition">
                        <x-table.td>{{ $loop->iteration }}</x-table.td>
                        <x-table.td primary>{{ $expense->expense_date->format('d M Y') }}</x-table.td>
                        <x-table.td>{{ $expense->budget->name ?? '-' }}</x-table.td>
                        <x-table.td>{{ Str::limit($expense->description, 40) ?? '-' }}</x-table.td>
                        <x-table.td><span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">{{ $expense->payment_method?->label() ?? '-' }}</span></x-table.td>
                        <x-table.td align="right" class="text-red-600 font-semibold">₹{{ number_format($expense->amount, 2) }}</x-table.td>
                        <x-table.td align="right">
                            <div class="flex justify-end items-center gap-3">
                                <x-actions.edit @click="$dispatch('open-update', { expense: {{ json_encode($expense) }} })" />
                                <x-actions.delete @click="$dispatch('open-delete', { expense: {{ json_encode($expense) }} })" />
                            </div>
                        </x-table.td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No expenses recorded. <button @click="$dispatch('open-store')" class="text-emerald-600 hover:underline">Add one</button>.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $expenses->links() }}</div>
    </div>

    @include('expenses.store')
    @include('expenses.update')
    @include('expenses.delete')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('expenseManager', () => ({
            updateAction: '{!! old('_method') === 'PUT' ? route('expenses.update', old('id', 0)) : '' !!}',
            deleteAction: '',

            form: {
                id: '{{ old('id') }}',
                category_id: '{{ old('category_id') }}',
                amount: '{{ old('amount') }}',
                expense_date: '{{ old('expense_date') }}',
                payment_method: '{{ old('payment_method') }}',
                reference_number: '{{ old('reference_number') }}',
                description: `{{ old('description') }}`
            },
            
            init() {
                window.addEventListener('open-store', () => {
                    this.$dispatch('open-modal', 'add-expense');
                });

                window.addEventListener('open-update', (e) => {
                    const exp = e.detail.expense;
                    this.updateAction = `/expenses/${exp.id}`;
                    this.form.id = exp.id;
                    this.form.category_id = exp.budget_id || exp.category_id;
                    this.form.amount = exp.amount;
                    this.form.expense_date = exp.expense_date ? exp.expense_date.split('T')[0] : '';
                    this.form.payment_method = exp.payment_method;
                    this.form.reference_number = exp.reference_number || '';
                    this.form.description = exp.description || '';
                    this.$dispatch('open-modal', 'edit-expense');
                });

                window.addEventListener('open-delete', (e) => {
                    const exp = e.detail.expense;
                    this.deleteAction = `/expenses/${exp.id}`;
                    this.form.amount = exp.amount;
                    this.$dispatch('open-modal', 'delete-expense');
                });

                @if($errors->storeExpense->any())
                    this.$dispatch('open-modal', 'add-expense');
                @endif
                
                @if($errors->updateExpense->any())
                    this.$dispatch('open-modal', 'edit-expense');
                @endif
            }
        }));
    });
</script>
@endsection
