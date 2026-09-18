@props(['data'])

<div class="bg-white p-6 shadow-sm border border-gray-200" style="font-family: 'Courier New', Courier, monospace; width: 100%; max-width: 380px; margin: 0 auto; color: #333; border-style: dashed; border-width: 1px;">
    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold tracking-widest mb-2">INVOICE</h2>
        <p class="text-sm font-semibold">{{ $data['company_name'] ?? 'Salon Admin Main Salon' }}</p>
        <p class="text-xs">{{ $data['company_address'] ?? 'Main Branch Address' }}</p>
        <p class="text-xs">GSTIN: {{ $data['company_gstin'] ?? '29ABCDE1234F1Z5' }}</p>
    </div>

    <!-- Details -->
    <div class="border-t border-dashed border-gray-300 pt-3 pb-3 mb-3 text-xs space-y-1">
        <div class="flex justify-between">
            <span>Date</span>
            <span>{{ $data['date'] ?? now()->format('Y-m-d h:i A') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Customer</span>
            <span class="font-semibold">{{ $data['customer_name'] ?? 'Customer Name' }}</span>
        </div>
        <div class="flex justify-between">
            <span>Email</span>
            <span>{{ $data['customer_email'] ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
            <span>Mobile</span>
            <span>{{ $data['customer_mobile'] ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
            <span>Payment Mode</span>
            <span>{{ $data['payment_mode'] ?? 'Pending' }}</span>
        </div>
    </div>

    <!-- Items -->
    <div class="border-t border-dashed border-gray-300 pt-3 pb-3 mb-3 text-xs">
        <div class="flex justify-between font-bold mb-2 uppercase text-gray-500">
            <span>Service/Products</span>
            <span>Cost</span>
        </div>
        
        @foreach($data['items'] ?? [] as $item)
            <div class="mb-2">
                <div class="flex justify-between font-medium">
                    <span>{{ $item['name'] }}</span>
                    <span>₹ {{ number_format($item['total'], 2) }}</span>
                </div>
                <div class="text-gray-500 text-[10px]">
                    {{ $item['qty'] }} x ₹ {{ number_format($item['price'], 2) }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Totals -->
    <div class="border-t border-dashed border-gray-300 pt-3 pb-3 mb-3 text-xs space-y-1">
        <div class="flex justify-between">
            <span>Service Cost</span>
            <span>₹ {{ number_format($data['subtotal'] ?? 0, 2) }}</span>
        </div>
        <div class="flex justify-between">
            <span>Subtotal</span>
            <span>₹ {{ number_format($data['subtotal'] ?? 0, 2) }}</span>
        </div>
        <div class="flex justify-between">
            <span>Tax ({{ $data['tax_rate'] ?? 5 }}%)</span>
            <span>₹ {{ number_format($data['tax_amount'] ?? 0, 2) }}</span>
        </div>
    </div>

    <!-- Grand Total -->
    <div class="border-t border-dashed border-gray-300 pt-3 pb-4 text-sm font-bold">
        <div class="flex justify-between">
            <span class="uppercase">Grand Total</span>
            <span>₹ {{ number_format($data['grand_total'] ?? 0, 2) }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="border-t border-dashed border-gray-300 pt-4 text-center text-[10px] text-gray-500">
        <p>Thank you for visiting {{ $data['company_name'] ?? 'Salon Admin Main Salon' }}. We look forward to serving you again.</p>
    </div>
</div>
