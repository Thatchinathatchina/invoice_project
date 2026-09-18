<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->string('currency_code', 3)->default('INR')->after('customer_id');
            $table->decimal('exchange_rate', 15, 6)->default(1)->after('currency_code');
        });

        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->string('currency_code', 3)->default('INR')->after('supplier_id');
            $table->decimal('exchange_rate', 15, 6)->default(1)->after('currency_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'exchange_rate']);
        });

        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'exchange_rate']);
        });
    }
};
