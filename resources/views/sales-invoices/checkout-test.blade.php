@extends('layouts.admin')
@section('title', 'Invoice Checkout')

@section('content')
@php
    // Dummy Data for the preview
    $invoiceData = [
        'date' => '2026-09-18 05:10 PM',
        'customer_name' => 'Customer User 1',
        'customer_email' => 'customer1@example.com',
        'customer_mobile' => '+91 9876543001',
        'payment_mode' => 'Split Bill',
        'items' => [
            ['name' => 'hair cut test', 'qty' => 1, 'price' => 587.08, 'total' => 587.08],
        ],
        'subtotal' => 587.08,
        'tax_rate' => 5,
        'tax_amount' => 29.35,
        'grand_total' => 616.43,
    ];
@endphp

<div x-data="checkoutFlow()" class="max-w-6xl mx-auto flex flex-col lg:flex-row gap-6">
    
    <!-- Left Column: Receipt Preview -->
    <div class="w-full lg:w-1/3 flex-shrink-0">
        <x-invoice.thermal-receipt :data="$invoiceData" />
    </div>

    <!-- Right Column: Interactive panels -->
    <div class="w-full lg:w-2/3">
        
        <!-- PAYMENT STEP -->
        <div x-show="step === 'payment'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-1">PAYMENT DETAILS</h3>
                <p class="text-sm text-gray-500">Configure Split Bill payment details here. Fields can be wired next.</p>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Amount</label>
                        <input type="text" readonly value="{{ $invoiceData['grand_total'] }}" class="w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm text-gray-800 font-bold focus:ring-red-500 focus:border-red-500 text-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Received By</label>
                        <input type="text" value="Salon Admin" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tips</label>
                        <input type="number" value="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mode 1</label>
                        <select x-model="mode1" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2">
                            <option value="Cash">Cash</option>
                            <option value="Card">Card</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount 1</label>
                        <input type="number" step="0.01" x-model.number="amount1" @input="calculateSplit()" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference 1</label>
                        <input type="text" placeholder="Txn / Ref" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2 text-gray-500 bg-gray-50">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mode 2</label>
                        <select x-model="mode2" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2">
                            <option value="Cash">Cash</option>
                            <option value="Card" selected>Card</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount 2 (Auto)</label>
                        <input type="number" step="0.01" x-model.number="amount2" readonly class="w-full rounded-lg bg-gray-50 border-gray-300 shadow-sm text-gray-500 text-sm py-2 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference 2</label>
                        <input type="text" placeholder="Txn / Ref" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2 text-gray-500 bg-gray-50">
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm text-gray-600 border-dashed">
                    Split Total: ₹<span x-text="(amount1 + amount2).toFixed(2)"></span> / Invoice Total: ₹{{ $invoiceData['grand_total'] }}
                </div>
                
                <div class="text-xs text-gray-500 text-center border-t border-gray-100 border-dashed pt-4">
                    This is a common modal for Cash, Wallet, Digital, Card, and Split Bill.
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex items-center space-x-4">
                <button type="button" class="px-6 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 transition">Close</button>
                <button @click="processPayment()" type="button" class="px-6 py-2.5 text-sm font-medium text-white bg-[#f03042] rounded-lg hover:bg-red-700 transition shadow-sm">Paid & Save</button>
            </div>
        </div>


        <!-- POST SAVE ACTIONS STEP -->
        <div x-show="step === 'actions'" x-cloak class="space-y-4">
            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-1">POST SAVE ACTIONS</h3>
                <p class="text-sm text-gray-500">Choose how to share or print this saved invoice.</p>
            </div>

            <!-- Email Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Email</label>
                <input type="email" value="{{ $invoiceData['customer_email'] }}" class="w-full rounded-lg border-red-500 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2 px-3 mb-2 bg-blue-50/30 text-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2">
                <p class="text-xs text-gray-500 mb-3">Verify the email before sending the invoice.</p>
                <button class="w-full py-2.5 bg-[#f03042] hover:bg-red-700 text-white rounded-lg text-sm font-medium transition shadow-sm">Send Email</button>
            </div>

            <!-- Mobile Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Mobile</label>
                <div class="flex rounded-lg shadow-sm mb-4">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">+91</span>
                    <input type="text" value="9876543001" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg focus:ring-red-500 focus:border-red-500 sm:text-sm border-gray-300">
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <button class="py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-sm font-medium transition">Send WhatsApp (Manual)</button>
                    <button class="py-2.5 bg-[#f03042] hover:bg-red-700 text-white rounded-lg text-sm font-medium transition shadow-sm opacity-80">Send WhatsApp (Automatic)</button>
                </div>
                <a href="#" class="text-xs text-gray-600 underline hover:text-gray-900">View Public Invoice Link</a>
            </div>

            <!-- Print Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-3">Print Invoice</label>
                <div class="grid grid-cols-2 gap-3">
                    <button class="py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-sm font-medium transition">Print Thermal</button>
                    <button class="py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-sm font-medium transition">Print A4</button>
                </div>
            </div>

            <!-- SMS Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">SMS Mobile</label>
                <div class="flex rounded-lg shadow-sm mb-4">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">+91</span>
                    <input type="text" value="9876543001" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg focus:ring-red-500 focus:border-red-500 sm:text-sm border-gray-300">
                </div>
                <button class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-sm font-medium transition">Send SMS Notification</button>
            </div>

            <div class="flex justify-end items-center space-x-4 pt-4">
                <button type="button" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Close</button>
                <button type="button" class="px-6 py-2 text-sm text-white bg-[#f03042] rounded-lg hover:bg-red-700 shadow-sm">Done</button>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkoutFlow', () => ({
            step: 'payment', // 'payment' or 'actions'
            total: {{ $invoiceData['grand_total'] }},
            mode1: 'Cash',
            mode2: 'Card',
            amount1: {{ $invoiceData['grand_total'] }},
            amount2: 0,
            
            init() {
                this.calculateSplit();
            },

            calculateSplit() {
                if (this.amount1 > this.total) {
                    this.amount1 = this.total;
                }
                if (this.amount1 < 0) this.amount1 = 0;
                
                this.amount2 = parseFloat((this.total - this.amount1).toFixed(2));
            },

            processPayment() {
                // Here we would typically submit the payment to the server
                // For demo purposes, we'll just transition to the next step
                this.step = 'actions';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }));
    });
</script>
@endsection
