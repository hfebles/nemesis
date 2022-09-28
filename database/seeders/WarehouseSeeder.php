<?php

namespace Database\Seeders;

use App\Models\Warehouse\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create([
            'name_warehouse' => 'producto terminado',
            'code_warehouse' => 'pt'
        ]);

        Warehouse::create([
            'name_warehouse' => 'materia prima',
            'code_warehouse' => 'mp'
        ]);

    }
}
