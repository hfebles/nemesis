<?php

namespace Database\Seeders;

use App\Models\Conf\Warehouse\UnitProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnitProduct::create(['name_unit_product' => 'KILO', 'short_unit_product' => 'KL']);
        UnitProduct::create(['name_unit_product' => 'MILIGRAMO', 'short_unit_product' => 'MG']);
        UnitProduct::create(['name_unit_product' => 'GRAMO', 'short_unit_product' => 'G']);
        UnitProduct::create(['name_unit_product' => 'METRO CUBICO', 'short_unit_product' => 'M3']);
        UnitProduct::create(['name_unit_product' => 'CENTIMERO CUBICO', 'short_unit_product' => 'CM3']);
        UnitProduct::create(['name_unit_product' => 'MILIMETRO CUBICO', 'short_unit_product' => 'ML3']);
        UnitProduct::create(['name_unit_product' => 'LITRO', 'short_unit_product' => 'L']);
        UnitProduct::create(['name_unit_product' => 'MILILITRO', 'short_unit_product' => 'ML']);
    }
}
