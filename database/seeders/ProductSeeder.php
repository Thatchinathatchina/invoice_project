<?php

namespace Database\Seeders;

use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Web Development Service',   'description' => 'Full-stack web application development',   'price' => 50000.00, 'type' => ProductType::SERVICE->value],
            ['name' => 'Mobile App Development',     'description' => 'Native/Cross-platform mobile app',        'price' => 75000.00, 'type' => ProductType::SERVICE->value],
            ['name' => 'UI/UX Design',               'description' => 'User interface and experience design',    'price' => 25000.00, 'type' => ProductType::SERVICE->value],
            ['name' => 'Cloud Hosting (Annual)',      'description' => 'Annual cloud server hosting package',     'price' => 12000.00, 'type' => ProductType::SERVICE->value],
            ['name' => 'Laptop - Dell Inspiron',      'description' => 'Dell Inspiron 15, i5, 8GB RAM, 512GB SSD','price' => 55000.00, 'type' => ProductType::PRODUCT->value],
            ['name' => 'Office Chair - Ergonomic',    'description' => 'Premium ergonomic office chair',          'price' => 8500.00,  'type' => ProductType::PRODUCT->value],
            ['name' => 'Printer - HP LaserJet',       'description' => 'HP LaserJet Pro MFP M428fdw',             'price' => 32000.00, 'type' => ProductType::PRODUCT->value],
            ['name' => 'Software License - Annual',   'description' => 'Annual software license subscription',    'price' => 15000.00, 'type' => ProductType::PRODUCT->value],
        ];
        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
