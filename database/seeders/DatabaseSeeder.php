<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Admin User ──────────────────────────────────────
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Call the separate seeders in the correct relational order
        $this->call([
            CustomerSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            BudgetSeeder::class,
            SalesInvoiceSeeder::class,
            PurchaseInvoiceSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully with separated seeders!');
    }
}
