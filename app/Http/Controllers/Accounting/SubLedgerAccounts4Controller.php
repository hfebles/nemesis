<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\SubLedgerAccounts4;
use Illuminate\Http\Request;

class SubLedgerAccounts4Controller extends Controller
{
    public function store(Request $request){

        $data = $request->except('_token');

        //return $data;

        $save = new SubLedgerAccounts4();
        $save->code_sub_ledger_account4 = $data['code_sub_ledger_account4'];
        $save->name_sub_ledger_account4 = strtoupper($data['name_sub_ledger_account4']);
        $save->id_sub_ledger_account3 = $data['id_sub_ledger_account3'];
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
