@extends('layouts.admin')
@section('title', 'Products & Services')
@section('subtitle', 'Manage your catalog')



@section('content')
<div x-data="productManager()">
    <form method="GET" action="{{ route('products.index') }}">
        <x-filters.bar>
            <x-filters.search placeholder="Search product name, code..." />
            <x-filters.select name="status">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </x-filters.select>
            <x-slot:actions>
                <button type="button" @click="$dispatch('open-store')" class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm" title="Add Product">
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                                {{ $product->type?->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">₹{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end items-center gap-3">
                                <x-actions.edit @click="$dispatch('open-update', { product: {{ json_encode($product) }} })" />
                                <x-actions.delete @click="$dispatch('open-delete', { product: {{ json_encode($product) }} })" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">No products found. <button @click="$dispatch('open-store')" class="text-emerald-600 hover:underline">Add one</button>.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>
    </div>

    <!-- Include Modals -->
    @include('products.store')
    @include('products.update')
    @include('products.delete')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productManager', () => ({
            updateAction: '{!! old('_method') === 'PUT' ? route('products.update', old('id', 0)) : '' !!}',
            deleteAction: '',

            form: {
                id: '{{ old('id') }}',
                name: '{{ old('name') }}',
                description: '{{ old('description') }}',
                price: '{{ old('price') }}',
                type: '{{ old('type', 1) }}',
                status: '{{ old('status', 'active') }}'
            },
            
            init() {
                window.addEventListener('open-store', () => {
                    this.$dispatch('open-modal', 'add-product');
                });

                window.addEventListener('open-update', (e) => {
                    const data = e.detail.product;
                    this.form = {
                        id: data.id,
                        name: data.name || '',
                        description: data.description || '',
                        price: data.price || 0,
                        type: data.type || 1,
                        status: data.status || 'active'
                    };
                    this.updateAction = `/products/${this.form.id}`;
                    this.$dispatch('open-modal', 'edit-product');
                });

                window.addEventListener('open-delete', (e) => {
                    const data = e.detail.product;
                    this.form.name = data.name;
                    this.deleteAction = `/products/${data.id}`;
                    this.$dispatch('open-modal', 'delete-product');
                });
            }
        }));
    });
</script>
@endsection
