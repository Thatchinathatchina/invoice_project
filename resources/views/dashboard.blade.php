@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Financial overview and key metrics')

@section('content')
<div class="space-y-6">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Sales Revenue --}}
        <x-cards.stat-primary title="Sales Revenue" value="₹{{ number_format($totalSalesRevenue, 2) }}" iconBg="bg-green-100" iconColor="text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        </x-cards.stat-primary>

        {{-- Total Purchases --}}
        <x-cards.stat-primary title="Total Purchases" value="₹{{ number_format($totalPurchases, 2) }}" iconBg="bg-red-100" iconColor="text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
        </x-cards.stat-primary>

        {{-- Total Expenses --}}
        <x-cards.stat-primary title="Total Expenses" value="₹{{ number_format($totalExpenses, 2) }}" iconBg="bg-orange-100" iconColor="text-orange-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </x-cards.stat-primary>

        {{-- Net Profit --}}
        <x-cards.stat-primary title="Net Profit" value="₹{{ number_format($netProfit, 2) }}" iconBg="bg-emerald-100" iconColor="text-emerald-600" valueClass="{{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </x-cards.stat-primary>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-cards.stat-secondary title="Customers" value="{{ $customerCount }}" iconBg="bg-blue-100" iconColor="text-blue-600" />
        <x-cards.stat-secondary title="Suppliers" value="{{ $supplierCount }}" iconBg="bg-purple-100" iconColor="text-purple-600" />
        <x-cards.stat-secondary title="Pending Invoices" value="{{ $pendingInvoices }}" iconBg="bg-yellow-100" iconColor="text-yellow-600" />
        <x-cards.stat-secondary title="Overdue Invoices" value="{{ $overdueInvoices }}" iconBg="bg-red-100" iconColor="text-red-600" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Sales Invoices --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Sales Invoices</h3>
                <a href="{{ route('sales-invoices.index') }}" class="text-sm text-emerald-600 hover:text-emerald-800">View All →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentSalesInvoices as $inv)
                    <div class="px-6 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $inv->invoice_number }}</p>
                            <p class="text-xs text-gray-500">{{ $inv->customer->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">₹{{ number_format($inv->total, 2) }}</p>
                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                                {{ $inv->status?->color() === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $inv->status?->color() === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $inv->status?->color() === 'red' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $inv->status?->color() === 'gray' ? 'bg-gray-100 text-gray-700' : '' }}
                            ">{{ $inv->status?->label() ?? 'Draft' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400">No sales invoices yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Purchase Invoices --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Purchase Invoices</h3>
                <a href="{{ route('purchase-invoices.index') }}" class="text-sm text-emerald-600 hover:text-emerald-800">View All →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentPurchaseInvoices as $inv)
                    <div class="px-6 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $inv->invoice_number }}</p>
                            <p class="text-xs text-gray-500">{{ $inv->supplier->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">₹{{ number_format($inv->total_amount, 2) }}</p>
                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                                {{ $inv->status?->color() === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $inv->status?->color() === 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $inv->status?->color() === 'red' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $inv->status?->color() === 'gray' ? 'bg-gray-100 text-gray-700' : '' }}
                            ">{{ $inv->status?->label() ?? 'Draft' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400">No purchase invoices yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Budget Overview --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Budget Overview</h3>
                <a href="{{ route('budgets.index') }}" class="text-sm text-emerald-600 hover:text-emerald-800">View All →</a>
            </div>
            <div class="p-6 space-y-4">
                @forelse($budgets as $budget)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $budget->name }}</span>
                            <span class="text-xs text-gray-500">₹{{ number_format($budget->total_spent, 2) }} / ₹{{ number_format($budget->amount, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full transition-all duration-500 {{ $budget->usage_percent > 90 ? 'bg-red-500' : ($budget->usage_percent > 70 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                 style="width: {{ min($budget->usage_percent, 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-gray-400 py-4">No active budgets.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
