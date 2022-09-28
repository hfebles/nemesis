<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MovesAccounts;
use Illuminate\Http\Request;

class MovesAccountsController extends Controller
{
    public function createMoves($ref, $type){

        $move = new MovesAccounts();

        $move->ref_moves_account = $ref;
        $move->type_moves_account = $type;
        $move->save();

        return ['id_move' => $move->id, 'type_move' => $type];
    }


    public function verMovimientos($id){

        $data = MovesAccounts::select('moves_accounts.*', 'accounting_entries.*')
        ->join('accounting_entries', 'accounting_entries.id_moves_account', '=', 'moves_accounts.id_moves_account')
        ->where('moves_accounts.ref_moves_account', '=', $id)
        ->get();

        


        $conf = [
            'title-section' => 'Asientos contables: ',
            'group' => 'accounting-ledger',
            'back' => "home",
        ];
        $debe=0;
        $haber=0;
        foreach($data as $kk){
            $debe = $debe+$kk->amount_debe_accounting_entries;
       }

       foreach($data as $ll){
            $haber = $haber+$ll->amount_haber_accounting_entries;
    }



        $totales = [
            'haber' =>$haber,
            'debe' =>$debe, 
        ];
        //return $totales;

        return view('accounting.moves-account.moves-show', compact('conf', 'data', 'totales'));
    }
}
