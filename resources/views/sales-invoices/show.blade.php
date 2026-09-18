<x-modal name="view-invoice" maxWidth="6xl">
    <div class="bg-gray-50 px-6 pt-6 pb-6 relative min-h-[80vh]">
        <div class="absolute top-4 right-4 z-10">
            <x-buttons.close-icon @click="$dispatch('close-modal', 'view-invoice')" />
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Saved Sales Invoice</h3>
            <p class="text-sm text-gray-500 mt-1" x-text="viewForm.invoice_number"></p>
        </div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row gap-8 items-start">
            {{-- Left side: Thermal Receipt --}}
            <div class="w-full md:w-1/3 flex justify-center md:sticky md:top-6 bg-white p-6 shadow-sm border border-gray-200" style="font-family: 'Courier New', Courier, monospace; max-width: 380px; color: #333; border-style: dashed; border-width: 1px;" id="thermal-receipt-container">
                <div class="w-full">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-bold tracking-widest mb-2">INVOICE</h2>
                        <p class="text-sm font-semibold">{{ config('app.name', 'InvoiceApp') }}</p>
                        <p class="text-xs">Main Branch Address</p>
                        <p class="text-xs">GSTIN: 29ABCDE1234F1Z5</p>
                    </div>

                    <!-- Details -->
                    <div class="border-t border-dashed border-gray-300 pt-3 pb-3 mb-3 text-xs space-y-1">
                        <div class="flex justify-between">
                            <span>Date</span>
                            <span x-text="viewForm.invoice_date_formatted"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Customer</span>
                            <span class="font-semibold" x-text="viewForm.customer_name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Email</span>
                            <span x-text="viewForm.customer_email || '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Mobile</span>
                            <span x-text="viewForm.customer_phone || '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Payment Mode</span>
                            <span x-text="viewForm.status"></span>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="border-t border-dashed border-gray-300 pt-3 pb-3 mb-3 text-xs">
                        <div class="flex justify-between font-bold mb-2 uppercase text-gray-500">
                            <span>Products</span>
                            <span>Cost</span>
                        </div>
                        
                        <template x-for="(item, index) in viewForm.items" :key="index">
                            <div class="mb-2">
                                <div class="flex justify-between font-medium">
                                    <span x-text="item.name"></span>
                                    <span x-text="'₹ ' + parseFloat(item.total).toFixed(2)"></span>
                                </div>
                                <div class="text-gray-500 text-[10px]" x-text="item.quantity + ' x ₹ ' + parseFloat(item.unit_price).toFixed(2)"></div>
                            </div>
                        </template>
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-dashed border-gray-300 pt-3 pb-3 mb-3 text-xs space-y-1">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span x-text="'₹ ' + parseFloat(viewForm.subtotal).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax</span>
                            <span x-text="'₹ ' + parseFloat(viewForm.tax_amount).toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- Grand Total -->
                    <div class="border-t border-dashed border-gray-300 pt-3 pb-4 text-sm font-bold">
                        <div class="flex justify-between">
                            <span class="uppercase">Grand Total (INR)</span>
                            <span x-text="'₹ ' + parseFloat(viewForm.total_amount).toFixed(2)"></span>
                        </div>
                        <template x-if="viewForm.currency_code !== 'INR'">
                            <div class="flex justify-between text-xs text-gray-500 mt-1 font-normal">
                                <span x-text="'Amount in ' + viewForm.currency_code"></span>
                                <span x-text="viewForm.currency_code + ' ' + (parseFloat(viewForm.total_amount) / parseFloat(viewForm.exchange_rate)).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-dashed border-gray-300 pt-4 text-center text-[10px] text-gray-500">
                        <p>Thank you for visiting. We look forward to serving you again.</p>
                    </div>
                </div>
            </div>

            {{-- Right side: Post Save Actions --}}
            <div class="w-full md:w-2/3 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-1">Post Save Actions</h3>
                    <p class="text-xs text-gray-500 mb-4">Choose how to share or print this saved invoice.</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    
                    {{-- Email Section --}}
                    <div class="p-5 border-b border-gray-100 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-lg shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-800">Email Invoice</h4>
                                <p class="text-xs text-gray-500 mb-3">Send this invoice directly to the customer's inbox.</p>
                                <div class="flex gap-2">
                                    <input type="email" class="flex-1 text-sm border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm py-2" x-model="viewForm.customer_email" placeholder="Email address">
                                    <button type="button" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm whitespace-nowrap">Send Email</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- WhatsApp Section --}}
                    <div class="p-5 border-b border-gray-100 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-lg shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="text-sm font-semibold text-gray-800">WhatsApp</h4>
                                    <a href="#" class="text-xs text-emerald-600 hover:underline">View Public Link</a>
                                </div>
                                <p class="text-xs text-gray-500 mb-3">Share via WhatsApp message.</p>
                                <div class="flex gap-2 mb-2">
                                    <div class="flex flex-1">
                                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">+91</span>
                                        <input type="text" class="flex-1 text-sm border-gray-300 rounded-r-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-2" x-model="viewForm.customer_phone" placeholder="Mobile number">
                                    </div>
                                    <button type="button" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm whitespace-nowrap">Auto Send</button>
                                </div>
                                <button type="button" class="w-full py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Open in WhatsApp (Manual)</button>
                            </div>
                        </div>
                    </div>

                    {{-- Print Section --}}
                    <div class="p-5 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="p-2.5 bg-gray-100 text-gray-600 rounded-lg shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-800">Print Options</h4>
                                <p class="text-xs text-gray-500 mb-3">Print thermal receipt or standard A4 format.</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" onclick="window.print()" class="flex items-center justify-center gap-2 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Thermal
                                    </button>
                                    <button type="button" class="flex items-center justify-center gap-2 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        A4 PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="$dispatch('close-modal', 'view-invoice')" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">Close</button>
                    <button type="button" @click="$dispatch('close-modal', 'view-invoice')" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">Done</button>
                </div>
            </div>
        </div>
    </div>
</x-modal>
<style>
    @media print {
        body * { visibility: hidden; }
        #thermal-receipt-container, #thermal-receipt-container * { visibility: visible; }
        #thermal-receipt-container { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%; 
            margin: 0; 
            padding: 0; 
            display: flex;
            justify-content: center;
        }
        aside, header, nav, .w-full.md\:w-2\/3, .no-print, [x-data="invoiceManager"] > div { display: none !important; }
    }
</style>
