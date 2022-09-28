<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\SubLedgerAccount;


class SubLedgerAccountController extends Controller
{
    public function store(Request $request){

        $data = $request->except('_token');

        //return $data;

        $save = new SubLedgerAccount();
        $save->code_sub_ledger_account = $data['code_sub_ledger_account'];
        $save->name_sub_ledger_account = strtoupper($data['name_sub_ledger_account']);
        $save->id_ledger_account = $data['id_ledger_account'];
        $save->id_type_ledger_account = $data['id_type_ledger_account'];
        $save->save();

        $message = [
            'type' => 'danger',
            'message' => 'Se registro con éxito',
        ];           

        return redirect()->back()->with('message', $message);

    }
}
