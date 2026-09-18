<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Tech Solutions Pvt Ltd',  'email' => 'info@techsolutions.com',  'phone' => '9800001001', 'address' => '10 IT Park',       'city' => 'Pune',      'state' => 'Maharashtra', 'country' => 'India', 'tax_number' => '27AAACT5645R1Z5'],
            ['name' => 'Global Supplies Co',      'email' => 'sales@globalsupplies.com','phone' => '9800001002', 'address' => '22 Industrial Area','city' => 'Hyderabad', 'state' => 'Telangana',   'country' => 'India', 'tax_number' => '36AAACG1234R1Z6'],
            ['name' => 'Star Office Equipments',  'email' => 'star@officeequip.com',    'phone' => '9800001003', 'address' => '5 Nehru Street',   'city' => 'Chennai',   'state' => 'Tamil Nadu',  'country' => 'India', 'tax_number' => '33AAACS7890R1Z7'],
        ];
        foreach ($suppliers as $s) {
            Supplier::create($s);
        }
    }
}
