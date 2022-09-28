<?php

namespace Database\Seeders;

use App\Models\Production\ProductionOrderState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionOrderStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1: A producir, 2: Producido, 3: Finalizado
        ProductionOrderState::create([
            'name_production_order_state' => 'A producir'
        ]);

        ProductionOrderState::create([
            'name_production_order_state' => 'Producido'
        ]);

        ProductionOrderState::create([
            'name_production_order_state' => 'Finalizado'
        ]);
    }
}
