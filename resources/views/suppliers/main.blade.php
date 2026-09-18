@extends('layouts.admin')
@section('title', 'Suppliers')
@section('subtitle', 'Manage your supplier records')



@section('content')
<div x-data="supplierManager()">
    <form method="GET" action="{{ route('suppliers.index') }}">
        <x-filters.bar>
            <x-filters.search placeholder="Search supplier name, email..." />
            <x-filters.select name="status">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </x-filters.select>
            <x-slot:actions>
                <button type="button" @click="$dispatch('open-store')" class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm" title="Add Supplier">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </x-slot:actions>
        </x-filters.bar>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">S.NO</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">City</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $supplier->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $supplier->email ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $supplier->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $supplier->city ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $supplier->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($supplier->status) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end items-center gap-3">
                                <x-actions.view href="{{ route('suppliers.show', $supplier) }}" />
                                <x-actions.edit @click="$dispatch('open-update', { supplier: {{ json_encode($supplier) }} })" />
                                <x-actions.delete @click="$dispatch('open-delete', { supplier: {{ json_encode($supplier) }} })" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No suppliers found. <button @click="$dispatch('open-store')" class="text-emerald-600 hover:underline">Add one</button>.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $suppliers->links() }}</div>
    </div>

    <!-- Include Modals -->
    @include('suppliers.store')
    @include('suppliers.update')
    @include('suppliers.delete')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('supplierManager', () => ({
            updateAction: '{!! old('_method') === 'PUT' ? route('suppliers.update', old('id', 0)) : '' !!}',
            deleteAction: '',

            form: {
                id: '{{ old('id') }}',
                name: '{{ old('name') }}',
                email: '{{ old('email') }}',
                phone: '{{ old('phone') }}',
                tax_number: '{{ old('tax_number') }}',
                address: '{{ old('address') }}',
                city: '{{ old('city') }}',
                status: '{{ old('status', 'active') }}'
            },
            
            init() {
                window.addEventListener('open-store', () => {
                    this.$dispatch('open-modal', 'add-supplier');
                });

                window.addEventListener('open-update', (e) => {
                    const data = e.detail.supplier;
                    this.form = {
                        id: data.id,
                        name: data.name || '',
                        email: data.email || '',
                        phone: data.phone || '',
                        tax_number: data.tax_number || '',
                        address: data.address || '',
                        city: data.city || '',
                        status: data.status || 'active'
                    };
                    this.updateAction = `/suppliers/${this.form.id}`;
                    this.$dispatch('open-modal', 'edit-supplier');
                });

                window.addEventListener('open-delete', (e) => {
                    const data = e.detail.supplier;
                    this.form.name = data.name;
                    this.deleteAction = `/suppliers/${data.id}`;
                    this.$dispatch('open-modal', 'delete-supplier');
                });
            }
        }));
    });
</script>
@endsection
