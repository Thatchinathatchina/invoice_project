<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Rajesh Kumar',    'email' => 'rajesh@example.com',    'phone' => '9876543001', 'address' => '12 MG Road',       'city' => 'Chennai',   'state' => 'Tamil Nadu',  'country' => 'India', 'tax_number' => '33AABCU9603R1ZM'],
            ['name' => 'Priya Sharma',    'email' => 'priya@example.com',     'phone' => '9876543002', 'address' => '45 Brigade Road',   'city' => 'Bangalore', 'state' => 'Karnataka',   'country' => 'India', 'tax_number' => '29AABCU9603R1ZP'],
            ['name' => 'Anand Mehta',     'email' => 'anand@example.com',     'phone' => '9876543003', 'address' => '78 Bandra West',    'city' => 'Mumbai',    'state' => 'Maharashtra', 'country' => 'India', 'tax_number' => '27AABCU9603R1ZQ'],
            ['name' => 'Lakshmi Iyer',    'email' => 'lakshmi@example.com',   'phone' => '9876543004', 'address' => '23 Anna Nagar',     'city' => 'Chennai',   'state' => 'Tamil Nadu',  'country' => 'India', 'tax_number' => '33AABCU9603R1ZR'],
            ['name' => 'Vikram Singh',    'email' => 'vikram@example.com',    'phone' => '9876543005', 'address' => '56 Connaught Place', 'city' => 'New Delhi', 'state' => 'Delhi',       'country' => 'India', 'tax_number' => '07AABCU9603R1ZS'],
        ];
        foreach ($customers as $c) {
            Customer::create($c);
        }
    }
}
