<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Discount::create([
            'name' => 'Diskon Hari Tani',
            'description' => 'Hari Tani Nasional',
            'type' => 'percentage',
            'value' => 20,
            'status' => 'active',
            'expired_date' => '2025-01-31'
        ]);

        \App\Models\Discount::create([
            'name' => 'Diskon 1 Tahun Toko',
            'description' => '1 Tahun Toko',
            'type' => 'percentage',
            'value' => 10,
            'status' => 'active',
            'expired_date' => '2025-01-07'
        ]);

        \App\Models\Discount::create([
            'name' => 'Diskon Jumat Berkah',
            'description' => 'Jumat Berkah',
            'type' => 'percentage',
            'value' => 15,
            'status' => 'active',
            'expired_date' => '2025-12-31'
        ]);
    }
}