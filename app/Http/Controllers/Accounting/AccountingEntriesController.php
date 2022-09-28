<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\AccountingEntries;
use App\Models\Accounting\SubLedgerAccount;
use App\Models\Accounting\TypeLedgerAccounts;
use App\Models\Conf\Bank;
use App\Models\Conf\Sales\InvoicingConfigutarion;
use App\Models\Conf\Tax;
use App\Models\Payments\Payments;
use App\Models\Sales\Invoicing;

class AccountingEntriesController extends Controller
{
    /**
     * id_move
     * type_move
     * 
     * si type_move = 1 es una venta
     * si type_move = 2 es una compra
     * si type_move = 3 es un pago
     * 
     * 
     */
    public function saveEntries($id_move, $type_move, $id_invoice){
        
        //$registerEntry = new AccountingEntries();

        if($type_move == 1){
            //$registerEntry->id_moves_account = $id_move;
            $dataConfig = InvoicingConfigutarion::all()[0];
            $dataTaxes = Tax::whereIdTax(1)->get()[0];
            $dataLedgersAccount = SubLedgerAccount::select('id_sub_ledger_account', 'name_sub_ledger_account')->where('name_sub_ledger_account', 'like', '%CXC CLIENTES%')->get()[0];
            $invoice = Invoicing::select('total_amount_invoicing', 'total_amount_tax_invoicing', 'ref_name_invoicing')->whereIdInvoicing($id_invoice)->get();
            

            //return $id_move;

            //CXC
            AccountingEntries::create([
                'id_moves_account' => $id_move,
                'id_ledger_account' => $dataLedgersAccount->id_sub_ledger_account,
                'description_accounting_entries' => $dataLedgersAccount->name_sub_ledger_account.'/'.$invoice[0]->ref_name_invoicing,
                'date_accounting_entries' => date('Y-m-d'),
                'amount_debe_accounting_entries' => floatval($invoice[0]->total_amount_invoicing),
            ]);

            //VENTA
            AccountingEntries::create([
                'id_moves_account' => $id_move,
                'id_ledger_account' => $dataTaxes->id_sub_ledger_account,
                'description_accounting_entries' => $invoice[0]->ref_name_invoicing,
                'date_accounting_entries' => date('Y-m-d'),
                'amount_haber_accounting_entries' => floatval($invoice[0]->total_amount_invoicing)-floatval($invoice[0]->total_amount_tax_invoicing),
            ]);

            //IVA
            AccountingEntries::create([
                'id_moves_account' => $id_move,
                'id_ledger_account' => $dataTaxes->id_sub_ledger_account,
                'description_accounting_entries' => $dataTaxes->name_tax.'/'.$invoice[0]->ref_name_invoicing,
                'date_accounting_entries' => date('Y-m-d'),
                'amount_haber_accounting_entries' => floatval($invoice[0]->total_amount_tax_invoicing),
            ]);

            

            
            
        }elseif ($type_move == 3) {
            $dataLedgersAccount = SubLedgerAccount::select('id_sub_ledger_account', 'name_sub_ledger_account')->where('name_sub_ledger_account', 'like', '%CXC CLIENTES%')->get()[0];
            $dataBank = Bank::select('id_sub_ledger_account', 'name_bank')->get()[0];
            $invoice = Invoicing::select('ref_name_invoicing')->whereIdInvoicing($id_invoice)->get();
            $payments = Payments::select('amount_payment')->whereIdInvoice($id_invoice)->orderBy('id_payment', 'DESC')->get()[0];

            //BANCO
            AccountingEntries::create([
                'id_moves_account' => $id_move,
                'id_ledger_account' => $dataBank->id_sub_ledger_account,
                'description_accounting_entries' => $dataBank->name_bank.'/'.$invoice[0]->ref_name_invoicing,
                'date_accounting_entries' => date('Y-m-d'),
                'amount_debe_accounting_entries' => floatval($payments->amount_payment),
            ]);

            //CXC
            AccountingEntries::create([
                'id_moves_account' => $id_move,
                'id_ledger_account' => $dataLedgersAccount->id_sub_ledger_account,
                'description_accounting_entries' => $dataLedgersAccount->name_sub_ledger_account.'/'.$invoice[0]->ref_name_invoicing,
                'date_accounting_entries' => date('Y-m-d'),
                'amount_haber_accounting_entries' => floatval($payments->amount_payment),
            ]);

            
        }
        
        return true;

        // $dataType = TypeLedgerAccounts::select('name_type_ledger_account')
        //                     ->join('sub_ledger_accounts', 'sub_ledger_accounts.id_type_ledger_account', '=', 'type_ledger_accounts.id_type_ledger_account')
        //                     ->where('sub_ledger_accounts.id_sub_ledger_account', '=', $id_ledger_account)
        //                     ->get();

        // if(count($dataType)>0){
            

        //     $registerEntry->id_ledger_account = $id_ledger_account;
        //     $registerEntry->ref_accounting_entries = $ref_accounting_entries;
        //     $registerEntry->description_accounting_entries = $description_accounting_entries;

        //     if($dataType[0]->name_type_ledger_account == 'ACTIVO'){
        //         $registerEntry->amount_debe_accounting_entries = floatval($amount)-floatval($amount_tax);
        //     }else{
        //         $registerEntry->amount_haber_accounting_entries = floatval($amount)-floatval($amount_tax);
        //     }
        //     $registerEntry->save();

        //     if($type == 1){
        //         $registerEntryTax = new AccountingEntries();
        //         $dataTaxes = Tax::whereIdTax($id_tax)->get()[0];
        //         $registerEntryTax->id_ledger_account = $dataTaxes->id_sub_ledger_account;
        //         $registerEntryTax->ref_accounting_entries = $ref_accounting_entries;
        //         $registerEntryTax->description_accounting_entries = $dataTaxes->name_tax.'/'.$description_accounting_entries;
        //         $dataType = TypeLedgerAccounts::select('name_type_ledger_account')
        //                     ->join('sub_ledger_accounts', 'sub_ledger_accounts.id_type_ledger_account', '=', 'type_ledger_accounts.id_type_ledger_account')
        //                     ->where('sub_ledger_accounts.id_sub_ledger_account', '=', $dataTaxes->id_sub_ledger_account)
        //                     ->get();
        //                     if($dataType[0]->name_type_ledger_account == 'ACTIVO'){
        //                         $registerEntryTax->amount_debe_accounting_entries = $amount_tax;
        //                     }else{
        //                         $registerEntryTax->amount_haber_accounting_entries = $amount_tax;
        //                     }
        //                     $registerEntryTax->save();
        //     }
        //     return true;
        // }else{
        //     return redirect()->route('sales-order.show', $ref_accounting_entries)->with('success', 'No se puede facturar porque no se tiene una cuenta contable asociada');
        // }

        

        


        
        
                            

        

    }
}
