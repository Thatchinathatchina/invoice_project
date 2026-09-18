@extends('layouts.admin')
@section('title', 'Budgets')
@section('subtitle', 'Manage monthly and yearly budgets')

@section('content')
<div x-data="budgetManager()">
    <form method="GET" action="{{ route('budgets.index') }}" class="ajax-filter">
        <x-filters.bar>
            <x-filters.search placeholder="Search budgets..." />
            <x-filters.select name="status">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </x-filters.select>
            <x-slot:actions>
                <button type="button" @click="$dispatch('open-store')" class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm" title="Add Budget">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </x-slot:actions>
        </x-filters.bar>
    </form>

    <div id="table-container" class="transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <x-table.th>Name</x-table.th>
                        <x-table.th>Period</x-table.th>
                        <x-table.th>Usage</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th align="right">Actions</x-table.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($budgets as $budget)
                        <tr class="hover:bg-gray-50 transition">
                            <x-table.td primary>{{ $budget->name }}</x-table.td>
                            <x-table.td>{{ $budget->start_date->format('d M Y') }} - {{ $budget->end_date->format('d M Y') }}</x-table.td>
                            <x-table.td>
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="text-gray-500">₹{{ number_format($budget->total_spent, 2) }}</span>
                                    <span class="font-medium">₹{{ number_format($budget->amount, 2) }}</span>
                                </div>
                                <div class="w-48 bg-gray-200 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all duration-500 {{ $budget->usage_percent > 90 ? 'bg-red-500' : ($budget->usage_percent > 70 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ min($budget->usage_percent, 100) }}%"></div>
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1 text-right">{{ $budget->usage_percent }}% used</div>
                            </x-table.td>
                            <x-table.td>
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $budget->status?->value === 1 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst(strtolower($budget->status?->name ?? 'Inactive')) }}
                                </span>
                            </x-table.td>
                            <x-table.td align="right">
                                <div class="flex justify-end items-center gap-3">
                                    <x-actions.edit @click="$dispatch('open-update', { budget: {{ json_encode($budget) }} })" />
                                    <x-actions.delete @click="$dispatch('open-delete', { budget: {{ json_encode($budget) }} })" />
                                </div>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">No budgets found. <button @click="$dispatch('open-store')" class="text-emerald-600 hover:underline">Add one</button>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-100">{{ $budgets->links() }}</div>
        </div>
    </div>

    @include('budgets.store')
    @include('budgets.update')
    @include('budgets.delete')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('budgetManager', () => ({
            updateAction: '{!! old('_method') === 'PUT' ? route('budgets.update', old('id', 0)) : '' !!}',
            deleteAction: '',

            form: {
                id: '{{ old('id') }}',
                name: '{{ old('name') }}',
                amount: '{{ old('amount') }}',
                start_date: '{{ old('start_date') }}',
                end_date: '{{ old('end_date') }}',
                description: `{{ old('description') }}`,
                status: '{{ old('status', 1) }}'
            },
            
            init() {
                window.addEventListener('open-store', () => {
                    this.$dispatch('open-modal', 'add-budget');
                });

                window.addEventListener('open-update', (e) => {
                    const b = e.detail.budget;
                    this.updateAction = `/budgets/${b.id}`;
                    this.form.id = b.id;
                    this.form.name = b.name;
                    this.form.amount = b.amount;
                    this.form.start_date = b.start_date ? b.start_date.split('T')[0] : '';
                    this.form.end_date = b.end_date ? b.end_date.split('T')[0] : '';
                    this.form.description = b.description || '';
                    this.form.status = b.status !== undefined && b.status !== null ? b.status : 1;
                    this.$dispatch('open-modal', 'edit-budget');
                });

                window.addEventListener('open-delete', (e) => {
                    const b = e.detail.budget;
                    this.deleteAction = `/budgets/${b.id}`;
                    this.form.name = b.name;
                    this.$dispatch('open-modal', 'delete-budget');
                });

                @if($errors->storeBudget->any())
                    this.$dispatch('open-modal', 'add-budget');
                @endif
                
                @if($errors->updateBudget->any())
                    this.$dispatch('open-modal', 'edit-budget');
                @endif
            }
        }));
    });
</script>
@endsection
