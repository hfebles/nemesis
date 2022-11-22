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
            'name_order_state' => strtoupper('Pedido por facturar'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Facturado'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Cancelado'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Abierta'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Pagado'),
        ]);

        // Notas de entregas

        OrderState::create([
            'name_order_state' => strtoupper('Nota de entrega'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Nota de entrega pagada'),
        ]);

        // COMPRAS

        OrderState::create([
            'name_order_state' => strtoupper('Pendiente de aprobacion'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Aprobada'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Cancelada'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Pendiente de recepcion'),
        ]);

        OrderState::create([
            'name_order_state' => strtoupper('Recibida'),
        ]);
    }
}
