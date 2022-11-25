<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Conf\Sales\InvoicingConfigutarionController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Sales\InvoicingController;
use Illuminate\Http\Request;
use App\Models\Accounting\AccountingEntries;
use App\Models\Accounting\LedgerAccount;
use App\Models\Accounting\MovesAccounts;
use App\Models\Accounting\SubLedgerAccount;
use App\Models\Accounting\TypeLedgerAccounts;
use App\Models\Conf\Bank;
use App\Models\Conf\Sales\InvoicingConfigutarion;
use App\Models\Conf\Tax;
use App\Models\Payments\Payments;
use App\Models\Payments\Surplus;
use App\Models\Purchase\Supplier;
use App\Models\Sales\Client;
use App\Models\Sales\Invoicing;

class AccountingEntriesController extends Controller
{
    /**
     * id_move
     * type_move
     * 
     * si type_move = 1 es una venta
     *  | DEBE | HABER |  
     *  | CXC  |       |
     *  |      | VENTA |
     *  |      | IVA   |
     * 
     *  
     * 
     * ==========================================================================================================
     * 
     * 
     * date_accounting_entries
     * amount_accounting_entries
     * id_ledger_account
     * description_accounting_entries
     * id_moves_account
     * id_invocing
     * 
     * 
     * 
     * ==========================================================================================================
     * si type_move = 2 es una compra
     *  | DEBE   | HABER |  
     *  | COMPRA |       |
     *  | IVA    |       |
     *  |        | CXP   |
     *  
     * si type_move = 3 es un pago de factura
     *  | DEBE  | HABER |  
     *  | BANCO |       |
     *  |       | CXC   |
     * 
     *  si type_move = 4 es un pago de compra
     *  | DEBE  | HABER |  
     *  | CXP   |       |
     *  |       | BANCO |
     * 
     * 
     */


    /*======================================== [ REGISTRO ventas] ======================================== */
    public function saveEntriesSales($move, $invocing)
    {

        $invoice = (new InvoicingController)->getDataInv($invocing);
        $conf = (new InvoicingConfigutarionController)->getInvConf();

        /*HACER MEJOR */

        $client = Client::find($invoice->id_client);

        // 1. Registramos la linea de la cuenta por cobrar: 
        AccountingEntries::create([
            'date_accounting_entries' => $invoice->date_invoicing,
            'amount_accounting_entries' => $invoice->total_amount_invoicing,
            'id_ledger_account' => 11,
            'description_accounting_entries' => 'CXC ACTIVO',
            'id_moves_account' => $move,
            'id_invocing' => $invocing,
        ]);

        // 2. Registramos la venta
        AccountingEntries::create([
            'date_accounting_entries' => $invoice->date_invoicing,
            'amount_accounting_entries' => $invoice->exempt_amout_invoicing + $invoice->no_exempt_amout_invoicing,
            'id_ledger_account' => $conf->id_ledger_account,
            'description_accounting_entries' => 'VENTAS',
            'id_moves_account' => $move,
            'id_invocing' => $invocing,
        ]);

        // 3. Registramos el iva
        if ($invoice->total_amount_tax_invoicing != 0) {
            AccountingEntries::create([
                'date_accounting_entries' => $invoice->date_invoicing,
                'amount_accounting_entries' => $invoice->total_amount_tax_invoicing,
                'id_ledger_account' => 76,
                'description_accounting_entries' => 'IVA PASIVO',
                'id_moves_account' => $move,
                'id_invocing' => $invocing,
            ]);
        }
    }

    /*======================================== [ REGISTRO COMPRAS] ======================================== */
    public function saveEntriesPurchase($move, $purchase)
    {

        $purchases = (new PurchaseController)->getDataPurchase($purchase);
        $conf = (new InvoicingConfigutarionController)->getInvConf();

        /*HACER MEJOR */

        $supplier = Supplier::find($purchases->id_supplier);

        // 1. Registramos la venta
        AccountingEntries::create([
            'date_accounting_entries' => $purchases->date_purchase,
            'amount_accounting_entries' => $purchases->exempt_amout_purchase + $purchases->no_exempt_amout_purchase,
            'id_ledger_account' => 121,
            'description_accounting_entries' => 'GASTO',
            'id_moves_account' => $move,
            'id_purchase' => $purchase,
        ]);

        // 2. Registramos el iva
        if ($purchases->total_amount_tax_purchase != 0) {
            AccountingEntries::create([
                'date_accounting_entries' => $purchases->date_purchase,
                'amount_accounting_entries' => $purchases->total_amount_tax_purchase,
                'id_ledger_account' => 24,
                'description_accounting_entries' => 'IVA COMPRA ACTIVO',
                'id_moves_account' => $move,
                'id_purchase' => $purchase,
            ]);
        }
        
        // 3. Registramos la linea de la cuenta por pagar: 
        AccountingEntries::create([
            'date_accounting_entries' => $purchases->date_purchase,
            'amount_accounting_entries' => $purchases->total_amount_purchase,
            'id_ledger_account' => 57,
            'description_accounting_entries' => 'CXP PASIVO',
            'id_moves_account' => $move,
            'id_purchase' => $purchase,
        ]);

        
    }
/*======================================== [ REGISTRO PAGO VENTA] ======================================== */
    public function saveEntriesPayments($move, $invocing, $amount, $bank)
    {

        $invoice = (new InvoicingController)->getDataInv($invocing);
        $conf = (new InvoicingConfigutarionController)->getInvConf();

        $banco = Bank::find($bank)->id_ledger_account;

        /*HACER MEJOR */

        $client = Client::whereIdClient($invoice->id_client)->get()[0];




        // 1. Registramos la linea del banco: 
        AccountingEntries::create([
            'date_accounting_entries' => $invoice->date_invoicing,
            'amount_accounting_entries' => $amount,
            'id_ledger_account' => $banco,
            'description_accounting_entries' => 'BANCO',
            'id_moves_account' => $move,
            'id_invocing' => $invocing,
        ]);

        // 2. Registramos el resto sobre la CXC

        AccountingEntries::create([
            'date_accounting_entries' => $invoice->date_invoicing,
            'amount_accounting_entries' => $amount,
            'id_ledger_account' => 57,
            'description_accounting_entries' => 'CXC ACTIVO',
            'id_moves_account' => $move,
            'id_invocing' => $invocing,
        ]);
    }

/*======================================== [ REGISTRO PAGO COMPRA] ======================================== */
    public function saveEntriesPaymentsPurchase($move, $purchase, $amount, $bank)
    {

        $purchases = (new PurchaseController)->getDataPurchase($purchase);
        $conf = (new InvoicingConfigutarionController)->getInvConf();

        //Proveedor
        $supplier = Supplier::find($purchases->id_supplier);

        

        

        // 1. Registramos el resto sobre la CXP
        AccountingEntries::create([
            'date_accounting_entries' => $purchases->date_purchase,
            'amount_accounting_entries' => $amount,
            'id_ledger_account' => 121,
            'description_accounting_entries' => 'CXP PASIVO',
            'id_moves_account' => $move,
            'id_purchase' => $purchase,
        ]);

        // 2. Registramos la linea del banco: 
        AccountingEntries::create([
            'date_accounting_entries' => $purchases->date_purchase,
            'amount_accounting_entries' => $amount,
            'id_ledger_account' => Bank::find($bank)->id_ledger_account,
            'description_accounting_entries' => 'BANCO',
            'id_moves_account' => $move,
            'id_purchase' => $purchase,
        ]);

        
    }
}
