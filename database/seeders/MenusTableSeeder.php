<?php

namespace Database\Seeders;

use App\Models\Conf\Menu;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create(['id' => 1, 'name' => 'Ventas', 'slug' => '/', 'grupo' => 'sales', 'icono' => 'fa-solid fa-file-invoice', 'parent' => '0', 'order' => '0', 'href' => '0',]);
        Menu::create(['id' => 2, 'name' => 'Facturación', 'slug' => '/', 'grupo' => 'invoices', 'icono' => 'fa-solid fa-file-invoice', 'parent' => '0', 'order' => '1', 'href' => '1', ]);
        Menu::create(['id' => 3, 'name' => 'Contabilidad', 'slug' => '/', 'grupo' => 'accounting-list', 'icono' => 'fa-sharp fa-solid fa-book', 'parent' => '0', 'order' => '2', 'href' => '0',]);
        Menu::create(['id' => 4, 'name' => 'Almacen', 'slug' => '/', 'grupo' => 'warehouse', 'icono' => 'fa-sharp fa-solid fa-warehouse', 'parent' => '0', 'order' => '3', 'href' => '0',]);
        Menu::create(['id' => 5, 'name' => 'Nomina', 'slug' => '/', 'grupo' => '', 'icono' => '', 'parent' => '0', 'order' => '4', 'href' => '0', 'enabled' => 0]);
        Menu::create(['id' => 6, 'name' => 'RRHH', 'slug' => '/', 'grupo' => 'rrhh', 'icono' => 'fa-sharp fa-solid fa-address-card', 'parent' => '0', 'order' => '5', 'href' => '1', ]);
        Menu::create(['id' => 7, 'name' => 'Configuraciones','slug' => '/','grupo' => 'mantenice','icono' => 'fa-solid fa-screwdriver-wrench','parent' => '0','order' => '9','href' => '0',]);
        Menu::create(['id' => 8, 'name' => 'Producción','slug' => '/','grupo' => 'production','icono' => 'fa-solid fa-screwdriver-wrench','parent' => '0','order' => '6','href' => '0',]);


        //VENTAS
        Menu::create(['id' => 9, 'name' => 'Clientes', 'slug' => 'clients.index', 'grupo' => 'sales-clients', 'icono' => 'fa-solid fa-users', 'parent' => '1', 'order' => '0', 'href' => '0', ]);
        Menu::create(['id' => 10, 'name' => 'Facturas', 'slug' => 'invoicing.index', 'grupo' => 'sales-invoices', 'icono' => 'fa-solid fa-file-invoice-dollar', 'parent' => '1', 'order' => '1', 'href' => '0', ]);
        Menu::create(['id' => 11, 'name' => 'Pedidos de venta', 'slug' => 'sales-order.index', 'grupo' => 'sales-order', 'icono' => 'fa-solid fa-receipt', 'parent' => '1', 'order' => '2', 'href' => '0', ]);
        Menu::create(['id' => 12, 'name' => 'Productos', 'slug' => 'product.salable', 'grupo' => 'product-product', 'icono' => 'fa-solid fa-table-list', 'parent' => '1', 'order' => '3', 'href' => '0', ]);

        //FACTURACION
        Menu::create(['id' => 13, 'name' => 'Facturas', 'slug' => 'invoicing.index', 'grupo' => 'sales-invoices', 'icono' => 'fa-solid fa-receipt', 'parent' => '2', 'order' => '0', 'href' => '0', ]);
        
        //CONTABILIDAD 
        Menu::create(['id' => 14, 'name' => 'Asientos contables', 'slug' => 'accounting-records.index', 'grupo' => 'accounting-records', 'icono' => 'fa-regular fa-address-book', 'parent' => '3', 'order' => '0', 'href' => '0', ]);
        Menu::create(['id' => 15, 'name' => 'Pagos Recibidos', 'slug' => 'payments.index', 'grupo' => 'payment', 'icono' => 'fa-sharp fa-solid fa-cash-register', 'parent' => '3', 'order' => '1', 'href' => '0', ]);
        Menu::create(['id' => 16, 'name' => 'Plan Contable', 'slug' => 'ledger-account.index', 'grupo' => 'accounting-ledger', 'icono' => 'fa-sharp fa-solid fa-table-list', 'parent' => '3', 'order' => '2', 'href' => '0', ]);

        //ALMACEN
        Menu::create(['id' => 17, 'name' => 'Almacenes', 'slug' => 'warehouse.index', 'grupo' => 'warehouse-warehouse', 'icono' => 'fa-sharp fa-solid fa-warehouse', 'parent' => '4', 'order' => '0', 'href' => '0', ]);
        Menu::create(['id' => 18, 'name' => 'Productos', 'slug' => 'product.index', 'grupo' => 'product-product', 'icono' => 'fa-sharp fa-solid fa-table-list', 'parent' => '4', 'order' => '1', 'href' => '0', ]);
        Menu::create(['id' => 19, 'name' => 'Categorias de productos', 'slug' => 'category.index', 'grupo' => 'product-category', 'icono' => 'fa-solid fa-gear', 'parent' => '4', 'order' => '1', 'href' => '0', ]);
        Menu::create(['id' => 20, 'name' => 'Unidades de medidas', 'slug' => 'unit.index', 'grupo' => 'product-unit', 'icono' => 'fa-solid fa-gear', 'parent' => '4', 'order' => '1', 'href' => '0', ]);
        Menu::create(['id' => 21, 'name' => 'Presentaciónes', 'slug' => 'presentation.index', 'grupo' => 'product-presentation', 'icono' => 'fa-solid fa-gear', 'parent' => '4', 'order' => '1', 'href' => '0', ]);
        

        
        //RRHH
        Menu::create(['id' => 22, 'name' => 'Trabajadores', 'slug' => 'workers.index', 'grupo' => 'rrhh-worker', 'icono' => 'fa-solid fa-user-tie', 'parent' => '6', 'order' => '0', 'href' => '0', ]);
        Menu::create(['id' => 23, 'name' => 'Grupos de trabajo', 'slug' => 'group-workers.index', 'grupo' => 'rrhh-group-worker', 'icono' => 'fa-solid fa-clipboard-user', 'parent' => '6', 'order' => '1', 'href' => '0', ]);


        //CONFIGURACIONES
        Menu::create(['id' => 24, 'name' => 'Bancos', 'slug' => 'banks.index', 'grupo' => 'banks', 'icono' => 'fa-solid fa-building-columns', 'parent' => '7', 'order' => '0', 'href' => '0', ]);
        Menu::create(['id' => 25, 'name' => 'Facturacion', 'slug' => 'invoices-config.index', 'grupo' => 'sales-invoices-conf', 'icono' => 'fa-solid fa-screwdriver-wrench', 'parent' => '7', 'order' => '1', 'href' => '0', ]);
        Menu::create(['id' => 26, 'name' => 'Impuestos','slug' => 'taxes.index','grupo' => 'taxes','icono' => 'fa-solid fa-money-bills','parent' => '7','order' => '2','href' => '0',]);
        Menu::create(['id' => 27, 'name' => 'Menús','slug' => 'menu.index','grupo' => 'menu','icono' => 'fa-sharp fa-solid fa-table-list','parent' => '7','order' => '3','href' => '0',]);
        Menu::create(['id' => 28, 'name' => 'Pedidos de venta','slug' => 'order-config.index','grupo' => 'sales-order-conf','icono' => 'fa-solid fa-screwdriver-wrench','parent' => '7','order' => '4','href' => '0',]);
        Menu::create(['id' => 29, 'name' => 'Permisologia','slug' => 'roles.index','grupo' => 'roles','icono' => 'fa-solid fa-users-rectangle','parent' => '7','order' => '5','href' => '0',]);
        Menu::create(['id' => 30, 'name' => 'Tasa de cambio','slug' => 'exchange.index','grupo' => 'exchange','icono' => 'fa-solid fa-building-columns','parent' => '7','order' => '6','href' => '0',]);
        Menu::create(['id' => 31, 'name' => 'Usuarios','slug' => 'users.index','grupo' => 'user','icono' => 'fa fa-address-card','parent' => '7','order' => '7','href' => '0',]);

        //PRODUCCION:
        Menu::create(['id' => 32, 'name' => 'Ordenes de produccion', 'slug' => 'production-order.index', 'grupo' => 'production-order', 'icono' => 'fa-solid fa-user-tie', 'parent' => '8', 'order' => '0', 'href' => '0', ]);
        Menu::create(['id' => 33, 'name' => 'Lista de materiales', 'slug' => 'material-list.index', 'grupo' => 'production-material-list', 'icono' => 'fa-solid fa-clipboard-user', 'parent' => '8', 'order' => '1', 'href' => '0', ]);

    }

}