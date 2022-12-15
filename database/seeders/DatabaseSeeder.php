<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(CreateAdminUserSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(LedgerAccountSeeder::class);  
        $this->call(CountrysSeeder::class);       
        $this->call(ProductsSeeder::class);     
        $this->call(UnitsProductsSeeder::class);  
        $this->call(PresentationProductsSeeder::class);  
        $this->call(SalesStatesSeeder::class);  
        $this->call(ProductionOrderStatesSeeder::class);  
        $this->call(WarehouseSeeder::class);  
        $this->call(AnotherConfigsSeeder::class);  
        
        
        
         
    }
}
