<?php

namespace Database\Seeders;

use App\Models\Sales\OrderState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderState::create([
            'name_order_state' => 'Pedido por facturar'
        ]);

        OrderState::create([
            'name_order_state' => 'Facturado'
        ]);

        OrderState::create([
            'name_order_state' => 'Cancelado'
        ]);

        OrderState::create([
            'name_order_state' => 'Abierta'
        ]);

        OrderState::create([
            'name_order_state' => 'Pagado'
        ]);
    }
}
