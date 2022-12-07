<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Conf\Sales\InvoicingConfigutarionController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Sales\InvoicingController;
use Illuminate\Http\Request;
use App\Models\Accounting\AccountingEntries;
use App\Models\Conf\Bank;
use App\Models\Purchase\Supplier;
use App\Models\Sales\Client;

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
     * ==========================================================================================================
     *
     * si type_move = 2 es una compra
     *  | DEBE   | HABER |  
     *  | COMPRA |       |
     *  | IVA    |       |
     *  |        | CXP   |
     * 
     * ==========================================================================================================
     *
     * si type_move = 3 es un pago de factura
     *  | DEBE  | HABER |  
     *  | BANCO |       |
     *  |       | CXC   |
     * 
     * ==========================================================================================================
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




        //VALIDAMOS EL TIPO DE CONTRIBUYENTE

        if ($client->taxpayer_client == 1) {
            // 1. AGENTE DE RETENCION O CONTRIBUYENTE ESPECIAL
            // 1.1. UBICAMOS EL MONTO DE LA RETENCION
            // 1.2. CONSULTAMOS PARA EL CASO DE 75% O 100%

            if ($client->porcentual_amount_tax_client == 75) {
                //1.2.1. Este cliente se le suma el 25% restante a la cuenta por cobrar
                $ret25 = $invoice->total_amount_tax_invoicing * 0.25;
                $ret75 = $invoice->total_amount_tax_invoicing * 0.75;
                $cxc = ($invoice->exempt_amout_invoicing + $invoice->no_exempt_amout_invoicing) + $ret25;

                // 1.2.2. Registramos la linea de la cuenta por cobrar: 
                AccountingEntries::create([
                    'date_accounting_entries' => $invoice->date_invoicing,
                    'amount_accounting_entries' => $cxc,
                    'id_ledger_account' => 11,
                    'description_accounting_entries' => 'CXC ACTIVO',
                    'id_moves_account' => $move,
                    'id_invocing' => $invocing,
                ]);

                // 1.2.3. Registramos LA RETENCION Del iva
                if ($invoice->total_amount_tax_invoicing != 0) {
                    AccountingEntries::create([
                        'date_accounting_entries' => $invoice->date_invoicing,
                        'amount_accounting_entries' => $ret75,
                        'id_ledger_account' => 26,
                        'description_accounting_entries' => 'RETENCION DE IVA CLIENTES (ACTIVO)',
                        'id_moves_account' => $move,
                        'id_invocing' => $invocing,
                    ]);
                }


                // 1.2.4. Registramos la venta
                AccountingEntries::create([
                    'date_accounting_entries' => $invoice->date_invoicing,
                    'amount_accounting_entries' => $invoice->exempt_amout_invoicing + $invoice->no_exempt_amout_invoicing,
                    'id_ledger_account' => $conf->id_ledger_account,
                    'description_accounting_entries' => 'VENTAS',
                    'id_moves_account' => $move,
                    'id_invocing' => $invocing,
                ]);

                // 1.2.5. Registramos el iva
                if ($invoice->total_amount_tax_invoicing != 0) {
                    AccountingEntries::create([
                        'date_accounting_entries' => $invoice->date_invoicing,
                        'amount_accounting_entries' => $invoice->total_amount_tax_invoicing,
                        'id_ledger_account' => 78,
                        'description_accounting_entries' => 'IVA PASIVO',
                        'id_moves_account' => $move,
                        'id_invocing' => $invocing,
                    ]);
                }

                // Registro de la retencion
                if ($invoice->total_amount_tax_invoicing != 0) {
                    (new WithholdingIvaSalesController)->registerRetention([
                        'id_invoice' => $invocing,
                        'id_client' => $invoice->id_client,
                        'amount_tax_invoice_whs' => $invoice->total_amount_tax_invoicing,
                        'amount_base_imponible_whs' => $invoice->exempt_amout_invoicing + $invoice->no_exempt_amout_invoicing,
                        'amount_tax_retention_whs' => $ret75,
                        'date_whs' => $invoice->date_invoicing,
                        'type_wh' => 1,
                    ]);
                }

            } else {
                //1.3. este cliente se le retiene el 100% del iva

                // Registro de la retencion
                if ($invoice->total_amount_tax_invoicing != 0) {
                    (new WithholdingIvaSalesController)->registerRetention([
                        'id_invoice' => $invocing,
                        'id_client' => $invoice->id_client,
                        'amount_tax_invoice_whs' => $invoice->total_amount_tax_invoicing,
                        'amount_base_imponible_whs' => $invoice->exempt_amout_invoicing + $invoice->no_exempt_amout_invoicing,
                        'amount_tax_retention_whs' => $invoice->total_amount_tax_invoicing,
                        'date_whs' => $invoice->date_invoicing,
                        'type_wh' => 1,
                    ]);
                }

                // 1.3.1. Registramos la linea de la cuenta por cobrar: 
                AccountingEntries::create([
                    'date_accounting_entries' => $invoice->date_invoicing,
                    'amount_accounting_entries' =>  $invoice->exempt_amout_invoicing + $invoice->no_exempt_amout_invoicing,
                    'id_ledger_account' => 11,
                    'description_accounting_entries' => 'CXC ACTIVO',
                    'id_moves_account' => $move,
                    'id_invocing' => $invocing,
                ]);

                // 1.3.2. Registramos LA RETENCION Del iva
                if ($invoice->total_amount_tax_invoicing != 0) {
                    AccountingEntries::create([
                        'date_accounting_entries' => $invoice->date_invoicing,
                        'amount_accounting_entries' => $invoice->total_amount_tax_invoicing,
                        'id_ledger_account' => 26,
                        'description_accounting_entries' => 'RETENCION DE IVA CLIENTES (ACTIVO)',
                        'id_moves_account' => $move,
                        'id_invocing' => $invocing,
                    ]);
                }

                // 1.3.3. Registramos la venta
                AccountingEntries::create([
                    'date_accounting_entries' => $invoice->date_invoicing,
                    'amount_accounting_entries' => $invoice->exempt_amout_invoicing + $invoice->no_exempt_amout_invoicing,
                    'id_ledger_account' => $conf->id_ledger_account,
                    'description_accounting_entries' => 'VENTAS',
                    'id_moves_account' => $move,
                    'id_invocing' => $invocing,
                ]);

                // 1.3.4. Registramos el iva
                if ($invoice->total_amount_tax_invoicing != 0) {
                    AccountingEntries::create([
                        'date_accounting_entries' => $invoice->date_invoicing,
                        'amount_accounting_entries' => $invoice->total_amount_tax_invoicing,
                        'id_ledger_account' => 78,
                        'description_accounting_entries' => 'IVA PASIVO',
                        'id_moves_account' => $move,
                        'id_invocing' => $invocing,
                    ]);
                }

                
            }
        } else {
            // 2. CONTRIBUYENTE ORDINARIO

            // 2.1. Registramos la linea de la cuenta por cobrar: 
            AccountingEntries::create([
                'date_accounting_entries' => $invoice->date_invoicing,
                'amount_accounting_entries' => $invoice->total_amount_invoicing,
                'id_ledger_account' => 11,
                'description_accounting_entries' => 'CXC ACTIVO',
                'id_moves_account' => $move,
                'id_invocing' => $invocing,
            ]);

            // 2.2. Registramos la venta
            AccountingEntries::create([
                'date_accounting_entries' => $invoice->date_invoicing,
                'amount_accounting_entries' => $invoice->exempt_amout_invoicing + $invoice->no_exempt_amout_invoicing,
                'id_ledger_account' => $conf->id_ledger_account,
                'description_accounting_entries' => 'VENTAS',
                'id_moves_account' => $move,
                'id_invocing' => $invocing,
            ]);

            // 2.3. Registramos el iva
            if ($invoice->total_amount_tax_invoicing != 0) {
                AccountingEntries::create([
                    'date_accounting_entries' => $invoice->date_invoicing,
                    'amount_accounting_entries' => $invoice->total_amount_tax_invoicing,
                    'id_ledger_account' => 78,
                    'description_accounting_entries' => 'IVA PASIVO',
                    'id_moves_account' => $move,
                    'id_invocing' => $invocing,
                ]);
            }
        }
    }

    /*======================================== [ REGISTRO COMPRAS] ======================================== */
    public function saveEntriesPurchase($move, $purchase)
    {

        $purchases = (new PurchaseController)->getDataPurchase($purchase);
        $conf = (new InvoicingConfigutarionController)->getInvConf();

        /*HACER MEJOR */

        $supplier = Supplier::find($purchases->id_supplier);


        // return $purchase;


        //Revisar peo mental. 

        if (env('TAX_PAYEER_COMPANY') == true) {
            //Contibuyente especial 

            if ($supplier->porcentual_amount_tax_supplier == 75) {
                //75
                $ret25 = $purchases->total_amount_tax_purchase * 0.25;
                $ret75 = $purchases->total_amount_tax_purchase * 0.75;
                $cxp = ($purchases->exempt_amout_purchase + $purchases->no_exempt_amout_purchase) + $ret25;

                // 1. Registramos la compra
                AccountingEntries::create([
                    'date_accounting_entries' => $purchases->date_purchase,
                    'amount_accounting_entries' => $purchases->exempt_amout_purchase + $purchases->no_exempt_amout_purchase,
                    'id_ledger_account' => 121,
                    'description_accounting_entries' => 'GASTO',
                    'id_moves_account' => $move,
                    'id_purchase' => $purchase,
                ]);

                // . Registramos la retencion del iva
                if ($purchases->total_amount_tax_purchase != 0) {
                    AccountingEntries::create([
                        'date_accounting_entries' => $purchases->date_purchase,
                        'amount_accounting_entries' => $purchases->total_amount_tax_purchase,
                        'id_ledger_account' => 26,
                        'description_accounting_entries' => 'IVA COMPRA ACTIVO',
                        'id_moves_account' => $move,
                        'id_purchase' => $purchase,
                    ]);
                }

                // 3. Registramos la linea de la cuenta por pagar: 
                AccountingEntries::create([
                    'date_accounting_entries' => $purchases->date_purchase,
                    'amount_accounting_entries' => $cxp,
                    'id_ledger_account' => 57,
                    'description_accounting_entries' => 'CXP PASIVO',
                    'id_moves_account' => $move,
                    'id_purchase' => $purchase,
                ]);

                // 2. Registramos el LA RETENCION DEL iva
                if ($purchases->total_amount_tax_purchase != 0) {
                    AccountingEntries::create([
                        'date_accounting_entries' => $purchases->date_purchase,
                        'amount_accounting_entries' => $ret75,
                        'id_ledger_account' => 84,
                        'description_accounting_entries' => 'IVA RETENCION COMPRA PASIVO',
                        'id_moves_account' => $move,
                        'id_purchase' => $purchase,
                    ]);
                }
              


                // Registro de la retencion
                if ($purchases->total_amount_tax_purchase != 0) {
                    (new WithholdingIvaPurchasesController)->registerRetention([
                        'id_purchase' => $purchase,
                        'id_supplier' => $purchases->id_supplier,
                        'amount_tax_invoice_whp' => $purchases->total_amount_tax_purchase,
                        'amount_base_imponible_whp' => $purchases->exempt_amout_purchase + $purchases->no_exempt_amout_purchase,
                        'amount_tax_retention_whp' => $ret75,
                        'date_whp' => $purchases->date_purchase,
                        'type_wh' => 2,
                    ]);
                }

                //return $algo;

            } else {
                //100

                // Registro de la retencion
                if ($purchases->total_amount_tax_purchase != 0) {
                    (new WithholdingIvaPurchasesController)->registerRetention([
                        'id_purchase' => $purchase,
                        'id_supplier' => $purchases->id_supplier,
                        'amount_tax_invoice_whp' => $purchases->total_amount_tax_purchase,
                        'amount_base_imponible_whp' => $purchases->exempt_amout_purchase + $purchases->no_exempt_amout_purchase,
                        'amount_tax_retention_whp' => $purchases->total_amount_tax_purchase,
                        'date_whp' => $purchases->date_purchase,
                        'type_wh' => 2,
                    ]);
                }

                AccountingEntries::create([
                    'date_accounting_entries' => $purchases->date_purchase,
                    'amount_accounting_entries' => $purchases->exempt_amout_purchase + $purchases->no_exempt_amout_purchase,
                    'id_ledger_account' => 121,
                    'description_accounting_entries' => 'GASTO',
                    'id_moves_account' => $move,
                    'id_purchase' => $purchase,
                ]);

                // 2. Registramos el LA RETENCION DEL iva
                if ($purchases->total_amount_tax_purchase != 0) {
                    AccountingEntries::create([
                        'date_accounting_entries' => $purchases->date_purchase,
                        'amount_accounting_entries' => $purchases->total_amount_tax_purchase,
                        'id_ledger_account' => 24,
                        'description_accounting_entries' => 'IVA RETENCION COMPRA PASIVO',
                        'id_moves_account' => $move,
                        'id_purchase' => $purchase,
                    ]);
                }

                // 3. Registramos la linea de la cuenta por pagar: 
                AccountingEntries::create([
                    'date_accounting_entries' => $purchases->date_purchase,
                    'amount_accounting_entries' => $purchases->exempt_amout_purchase + $purchases->no_exempt_amout_purchase,
                    'id_ledger_account' => 57,
                    'description_accounting_entries' => 'CXP PASIVO',
                    'id_moves_account' => $move,
                    'id_purchase' => $purchase,
                ]);

                // . Registramos la retencion del iva
                if ($purchases->total_amount_tax_purchase != 0) {
                    AccountingEntries::create([
                        'date_accounting_entries' => $purchases->date_purchase,
                        'amount_accounting_entries' => $purchases->total_amount_tax_purchase,
                        'id_ledger_account' => 26,
                        'description_accounting_entries' => 'IVA COMPRA ACTIVO',
                        'id_moves_account' => $move,
                        'id_purchase' => $purchases->id_purchase,
                    ]);
                }
                if ($purchases->total_amount_tax_purchase != 0) {
                    (new WithholdingIvaPurchasesController)->registerRetention([
                        'id_purchase' => $purchase,
                        'id_supplier' => $purchases->id_supplier,
                        'amount_tax_invoice_whp' => $purchases->total_amount_tax_purchase, 
                        'amount_base_imponible_whp' => $purchases->exempt_amout_purchase + $purchases->no_exempt_amout_purchase,
                        'amount_tax_retention_whp' => $purchases->total_amount_tax_purchase,
                        'date_whp' => $purchases->date_purchase,
                        'type_wh' => 2,
                    ]);
                }
            }
        } else {
            //Contibuyente ordinario 

            // 1. Registramos la compra
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
    }
    /*======================================== [ REGISTRO PAGO VENTA] ======================================== */
    public function saveEntriesPayments($move, $invocing, $amount, $bank)
    {

        $invoice = (new InvoicingController)->getDataInv($invocing);
        $conf = (new InvoicingConfigutarionController)->getInvConf();

        $banco = Bank::find($bank)->id_ledger_account;

        /*HACER MEJOR */

        $client = Client::find($invoice->id_client);


        // 1. Registramos la linea del banco: 
        AccountingEntries::create([
            'date_accounting_entries' => $invoice->date_invoicing,
            'amount_accounting_entries' => $amount,
            'id_ledger_account' => $banco,
            'description_accounting_entries' => 'BANCO/'.$invoice->ref_name_invoicing,
            'id_moves_account' => $move,
            'id_invocing' => $invocing,
        ]);

        // 2. Registramos el resto sobre la CXC

        AccountingEntries::create([
            'date_accounting_entries' => $invoice->date_invoicing,
            'amount_accounting_entries' => $amount,
            'id_ledger_account' => 11,
            'description_accounting_entries' => 'CXC ACTIVO/'.$invoice->ref_name_invoicing,
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
            'id_ledger_account' => 57,
            'description_accounting_entries' => 'CXP PASIVO',
            'id_moves_account' => $move,
            'id_purchase' => $purchase,
        ]);

        // 2. Registramos la linea del banco: 
        AccountingEntries::create([
            'date_accounting_entries' => $purchases->date_purchase,
            'amount_accounting_entries' => $amount,
            'id_ledger_account' => Bank::find($bank)->id_ledger_account,
            'description_accounting_entries' => 'BANCO'.$purchases->ref_name_purchase,
            'id_moves_account' => $move,
            'id_purchase' => $purchase,
        ]);
    }
}
