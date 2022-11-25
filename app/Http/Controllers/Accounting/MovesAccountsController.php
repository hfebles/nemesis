<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\LedgerAccount;
use App\Models\Accounting\MovesAccounts;
use App\Models\Accounting\TypeLedgerAccounts;
use Illuminate\Http\Request;

class MovesAccountsController extends Controller
{

    public function index(Request $request)
    {
        $conf = [
            'title-section' => 'Asientos contables',
            'group' => 'sales-invoices',
            'create' => ['route' => 'invoicing.create', 'name' => 'Nuevo asiento'],
        ];

        $data = MovesAccounts::select(
            'id_moves_account',
            'ref_name_invoicing',
            'ref_name_purchase',
            'date_moves_account',)
            ->join('invoicings', 'invoicings.id_invoicing', '=', 'moves_accounts.id_invocing', 'left outer')
            ->join('purchases', 'purchases.id_purchase', '=', 'moves_accounts.id_purchase', 'left outer')
            ->paginate(15);

        //return ;

        $table = [
            'c_table' => 'table table-sm table-bordered table-hover mb-0 ',
            'c_thead' => 'bg-dark text-white',
            'ths' => ['#', 'Fecha', 'Factura/Pedido', 'Asiento Nro'],
            'w_ts' => ['3', '10', '50', '36',],
            'c_ths' =>
            [
                'text-start align-middle p-1',
                'text-start align-middle p-1',
                'text-start align-middle p-1',
                'align-middle',
            ],

            'tds' => ['date_moves_account', 'ref_name_invoicing', 'id_moves_account',],
            'switch' => false,
            'edit' => false,
            'edit_modal' => false,
            'show' => true,
            'group' => 'accounting-ledger',
            'url' => '/accounting/moves',
            'id' => 'id_moves_account',
            'data' => $data,
            'i' => (($request->input('page', 1) - 1) * 15),
        ];


        return view('accounting.moves-account.index', compact('table', 'conf'));
    }


    public function show($id)
    {

        $type_moves_account = MovesAccounts::find($id)->type_moves_account;


        //return $type_moves_account;

        $data = MovesAccounts::select('moves_accounts.*', 'accounting_entries.*', 'ledger_accounts.id_type_ledger_account')
            ->join('accounting_entries', 'accounting_entries.id_moves_account', '=', 'moves_accounts.id_moves_account')
            ->join('ledger_accounts', 'accounting_entries.id_ledger_account', '=', 'ledger_accounts.id_ledger_account')
            ->where('moves_accounts.id_moves_account', '=', $id)
            ->get();



        $debe = 0;
        $haber = 0;
        if ($type_moves_account == 1 || $type_moves_account == 3) {
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]->id_type_ledger_account == 1 || $data[$i]->id_type_ledger_account == 5 || $data[$i]->id_type_ledger_account == 6) {
                    $data[$i]->monto_debe = $data[$i]->amount_accounting_entries;
                    $debe = $debe + $data[$i]->monto_debe;
                } else {
                    $data[$i]->monto_haber = $data[$i]->amount_accounting_entries;
                    $haber = $haber + $data[$i]->monto_haber;
                }
            }
        }


        if ($type_moves_account == 2 || $type_moves_account == 4) {
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]->id_type_ledger_account == 2 || $data[$i]->id_type_ledger_account == 3 || $data[$i]->id_type_ledger_account == 4) {
                    $data[$i]->monto_haber = $data[$i]->amount_accounting_entries;
                    $haber = $haber + $data[$i]->monto_haber;
                } elseif ($data[$i]->id_type_ledger_account == 1) {
                    $data[$i]->monto_haber = $data[$i]->amount_accounting_entries;
                    $haber = $haber + $data[$i]->monto_haber;
                } else {
                    $data[$i]->monto_debe = $data[$i]->amount_accounting_entries;
                    $debe = $debe + $data[$i]->monto_debe;
                }
            }
        }








        $totales = ['debe' => $debe, 'haber' => $haber];

        $conf = [
            'title-section' => 'Asientos contables: ',
            'group' => 'accounting-ledger',
            'back' => "moves.index",
        ];
        //return $data;
        return view('accounting.moves-account.show', compact('conf', 'data', 'totales'));
    }

    public function reports($id)
    {

        $nameLedger = LedgerAccount::find($id)->name_ledger_account;

        $conf = [
            'title-section' => $nameLedger,
            'group' => 'sales-invoices',
            'create' => ['route' => 'invoicing.create', 'name' => 'Nuevo asiento'],
        ];

        $data = MovesAccounts::select('moves_accounts.*', 'accounting_entries.*',)
            ->join('accounting_entries', 'accounting_entries.id_moves_account', '=', 'moves_accounts.id_moves_account')
            ->where('accounting_entries.id_ledger_account', '=', $id)
            ->get();


        $debe = 0;
        $haber = 0;
        for ($i = 0; $i < count($data); $i++) {

            if ($data[$i]->type_moves_account == 1) {
                $debe = $debe + $data[$i]->amount_accounting_entries;
            } else {
                $haber = $haber + $data[$i]->amount_accounting_entries;
            }
        }

        $totales = ['debe' => $debe, 'haber' => $haber];
        return view('accounting.moves-account.reportes.mayor', compact('data', 'conf', 'totales'));
    }


    /**
     * 
     * 
     * monto / amount_moves_account
     * cuenta /id_ledger_account
     * tipo / type_moves_account
     * factura / id_invocing
     * Fecha / date_moves_account
     * 
     * 
     */
    public function createMoves($invoicing, $date, $type)
    {

        $move = new MovesAccounts();
        $move->id_invocing = $invoicing;
        $move->type_moves_account = $type;
        $move->date_moves_account = $date;
        $move->save();

        return  $move->id_moves_account;
    }

    public function createMovesPurchase($purchase, $date, $type)
    {

        $move = new MovesAccounts();
        $move->id_purchase = $purchase;
        $move->type_moves_account = $type;
        $move->date_moves_account = $date;
        $move->save();

        return  $move->id_moves_account;
    }
}
