<?php

namespace Database\Seeders;

use App\Models\Conf\Warehouse\PresentationProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PresentationProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PresentationProduct::create(['name_presentation_product' => 'CAJA',]);
        PresentationProduct::create(['name_presentation_product' => 'EMPAQUE',]);
        PresentationProduct::create(['name_presentation_product' => 'BOLSA',]);

    }
}
