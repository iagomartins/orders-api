<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TravelOrders;

class TravelOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TravelOrders::factory()->create([
            'customer_name' => 'Teste Iago',
            'destiny' => 'Austrália',
            'start_date' => date_create(),
            'return_date' => date_create(),
            'status' => 'Approved'
        ]);
    }
}
