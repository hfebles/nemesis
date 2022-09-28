<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\SubLedgerAccounts2;
use Illuminate\Http\Request;

class SubLedgerAccounts2Controller extends Controller
{
    public function store(Request $request){

        $data = $request->except('_token');

        //return $data;

        $save = new SubLedgerAccounts2();
        $save->code_sub_ledger_account2 = $data['code_sub_ledger_account2'];
        $save->name_sub_ledger_account2 = strtoupper($data['name_sub_ledger_account2']);
        $save->id_sub_ledger_account = $data['id_sub_ledger_account'];
        $save->id_type_ledger_account = $data['id_type_ledger_account'];
        $save->save();

        //return redirect()->route('ledger-account.show',  $data['id_group']);
        $message = [
            'type' => 'danger',
            'message' => 'Se registro con éxito',
        ];           

        return redirect()->back()->with('message', $message);

    }
}
