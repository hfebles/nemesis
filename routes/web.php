<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\Conf\RoleController;
use App\Http\Controllers\Conf\UserController;
use App\Http\Controllers\Conf\CompaniaController;
use App\Http\Controllers\Conf\MenuController;
use App\Http\Controllers\Conf\Warehouse\ProductCategoryController;
use App\Http\Controllers\Conf\Warehouse\PresentationProductController;
use App\Http\Controllers\Conf\Warehouse\UnitProductController;
use App\Http\Controllers\Conf\ExchangeController;
use App\Http\Controllers\Conf\TaxController;

use App\Http\Controllers\Conf\Sales\SaleOrderConfigurationController;



use App\Http\Controllers\Accounting\LedgerAccountController;
use App\Http\Controllers\Accounting\SubLedgerAccountController;
use App\Http\Controllers\Accounting\SubGroupController;
use App\Http\Controllers\Accounting\GroupController;
use App\Http\Controllers\Accounting\AccountingEntriesController;
use App\Http\Controllers\Accounting\MovesAccountsController;
use App\Http\Controllers\Accounting\RecordAccoutingController;
use App\Http\Controllers\Accounting\SubLedgerAccounts2Controller;
use App\Http\Controllers\Accounting\SubLedgerAccounts3Controller;
use App\Http\Controllers\Accounting\SubLedgerAccounts4Controller;
use App\Http\Controllers\Warehouse\WarehouseController;

use App\Http\Controllers\Products\ProductController;

use App\Http\Controllers\Sales\ClientController;
use App\Http\Controllers\Sales\SalesOrderController;




use App\Http\Controllers\HumanResources\WorkersController;
use App\Http\Controllers\HumanResources\GroupWorkersController;


use App\Http\Controllers\Conf\BankController;
use App\Http\Controllers\Conf\Sales\InvoicingConfigutarionController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Production\MaterialsListController;
use App\Http\Controllers\Production\ProductionOrderController;
use App\Http\Controllers\Sales\InvoicingController;

Auth::routes();

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::group(['middleware' => ['auth']], function() {


    /**
     * 
     * CONFIGURACIONES
     * 
     */

    
    Route::resource('/mantenice/roles', RoleController::class);
    
    // Users
    Route::resource('/mantenice/users', UserController::class);
    Route::get('/mantenice/users/profile/{id}', [UserController::class, 'profile'])->name('users.profile');


    // Compañia
    Route::resource('/mantenice/compania', CompaniaController::class);

    // menus
    Route::resource('/mantenice/menu', MenuController::class);

    // PRODUCTOS
    Route::resource('/mantenice/product/category', ProductCategoryController::class);
    Route::resource('/mantenice/product/unit', UnitProductController::class);
    Route::resource('/mantenice/product/presentation', PresentationProductController::class);

    // TASA BCV
    Route::resource('/mantenice/exchange', ExchangeController::class);

    // Impuestos 
    Route::resource('/mantenice/taxes', TaxController::class);
    Route::post('/mantenice/edit-taxes', [TaxController::class, 'editModal'])->name('taxes.edit-tax');
    // bancos 
    Route::resource('/mantenice/banks', BankController::class);
    Route::post('/mantenice/edit-banks', [BankController::class, 'editModal'])->name('banks.edit-banks');

    //conf pedidos

    Route::resource('/mantenice/sales-order/order-config', SaleOrderConfigurationController::class);
    Route::resource('/mantenice/invoices/invoices-config', InvoicingConfigutarionController::class);

    /**
     * 
     * FIN CONFIGURACIONES
     * 
     */


    /**
     * 
     * CONTABILIDAD
     * 
     */

    // PLAN CONTABLE
    Route::resource('accounting/ledger-account', LedgerAccountController::class);
    Route::resource('accounting/sub-ledger-account', SubLedgerAccountController::class);
    Route::resource('accounting/sub-group-accounting', SubGroupController::class);
    Route::resource('accounting/group-accounting', GroupController::class);

    Route::resource('accounting/sub-ledger-account2', SubLedgerAccounts2Controller::class);
    Route::resource('accounting/sub-ledger-account3', SubLedgerAccounts3Controller::class);
    Route::resource('accounting/sub-ledger-account4', SubLedgerAccounts4Controller::class);


    // ASIENTOS CONTABLES
    Route::resource('accounting/accounting-entries', AccountingEntriesController::class);
    Route::resource('accounting/accounting-records', RecordAccoutingController::class);

    // MOVIMIENTOS 

    Route::resource('/accounting/moves/', MovesAccountsController::class);
    Route::get('/accounting/moves/moves-show/{id}', [MovesAccountsController::class, 'verMovimientos'])->name('moves.moves-show');

    /**
     * 
     * FIN CONTABILIDAD
     * 
     */


    /**
     * 
     * VENTAS
     * 
     */
    
    // CLIENTES

    Route::resource('/sales/clients', ClientController::class);
    Route::post('/sales/clients/search', [ClientController::class, 'searchCliente'])->name('clients.search-client');
    

    //PEDIDOS

    Route::resource('/sales/sales-order', SalesOrderController::class);
    Route::post('/sales/search', [SalesOrderController::class, 'listar'])->name('sales.search');
    Route::post('/sales/availability', [SalesOrderController::class, 'disponible'])->name('sales.check-aviavility');
    Route::get('/sales/cancel/{id}', [SalesOrderController::class, 'anular'])->name('sales.cancel');


    // Facturacion
    Route::get('/sales/invoicing/validate/{id}', [InvoicingController::class, 'validarPedido'])->name('sales.invoices-validate');
    Route::resource('/sales/invoicing', InvoicingController::class);
    Route::get('/sales/invoicing/print/{id}', [InvoicingController::class, 'imprimirFactura'])->name('sales.invoices-print');
    Route::get('/sales/cancel-invoicing/{id}', [InvoicingController::class, 'anularFactura'])->name('sales.cancel-invoices');
    






     /**
     * 
     * FIN VENTAS
     * 
     */


    /**
     * 
     * ALMACEN
     * 
     */
    
    // ALMACEN

    Route::resource('/warehouse/warehouse', WarehouseController::class);
    
     /**
     * 
     * FIN ALMACEN
     * 
     */

     /**
     * 
     * PAYMENTS
     * 
     */
    
    // PAYMENTS

    Route::resource('/accounting/payments', PaymentController::class);
    
    /**
    * 
    * FIN PAYMENTS
    * 
    */


     /**
     * 
     * PRODUCTOS
     * 
     */
    
    // PRODUCTOS

    Route::get('/products/product/salable', [ProductController::class, 'indexSalable'])->name('product.salable');
    Route::resource('/products/product', ProductController::class);
    Route::post('/products/product/search-code', [ProductController::class, 'searchCode'])->name('product.search-code');
    Route::post('/products/search', [ProductController::class, 'search'])->name('product.search');

    
    
    /**
    * 
    * FIN PRODUCTOS
    * 
    */



         /**
     * 
     * RECURSOS HUMANOS
     * 
     */
    
    // TRABAJADORES
    Route::resource('/hhrr/workers', WorkersController::class);
    Route::post('/hhrr/workers/search-dni', [WorkersController::class, 'searchCedula'])->name('workers.search-dni');
    
    // GRUPOS DE TRABAJO
    Route::resource('/hhrr/group-workers', GroupWorkersController::class);
    Route::post('/hhrr/edit-group', [GroupWorkersController::class, 'editModal'])->name('workers.edit-group');

    
    
    /**
    * 
    * FIN RECURSOS HUMANOS
    * 
    */


         /**
     * 
     * PRODUCCION
     * 
     */
    
    // ordenes de produccion
    Route::resource('/production/production-order', ProductionOrderController::class);
    Route::post('/production/material-list-search', [ProductionOrderController::class, 'traerLista'])->name('production-order.material-list-search');
    Route::get('/production/aprove/{id}', [ProductionOrderController::class, 'aprove'])->name('production-order.aprove');
    Route::get('/production/finalice/{id}', [ProductionOrderController::class, 'finalice'])->name('production-order.finalice');
    

    
    // lista de materiales
    Route::resource('/production/material-list', MaterialsListController::class);
    


    
    
    /**
    * 
    * FIN PRODUCCION
    * 
    */
     
     

});
