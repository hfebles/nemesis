<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\Group;
use App\Models\Accounting\LedgerAccount;
use App\Models\Accounting\SubGroup;


class GroupController extends Controller
{
    public function index(){


   

        Group::truncate();        
        $csvFile = fopen(base_path("database/data/grupo.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            var_dump($data[0]);
            if (!$firstline) {
                LedgerAccount::create([
                    "code_ledger_account" => $data[1],
                    "name_ledger_account" => $data[2],
                    "id_type_ledger_account" => $data[3],
                ]);            
            }
            $firstline = false;
        }
        fclose($csvFile);


        SubGroup::truncate();        
        $csvFile = fopen(base_path("database/data/subgrupo.csv"), "r");
        $firstline = true;
        while (($data2 = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                SubGroup::create([
                    "id_sub_group" => $data2[0],
                    "id_group" => $data2[1],
                    "code_sub_group" => $data2[2],
                    "name_sub_group" => $data2[3]
                ]);              
            }
            $firstline = false;
        }
        fclose($csvFile);

        LedgerAccount::truncate();        
        $csvFile = fopen(base_path("database/data/cuenta.csv"), "r");
        $firstline = true;
        while (($data3 = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            //var_dump($data3);
            if (!$firstline) {
                LedgerAccount::create([
                    "id_ledger_account" => $data3[0],
                    "id_sub_group" => $data3[1],
                    "code_ledger_account" => $data3[2],
                    "name_ledger_account" => $data3[3]
                ]);               
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
    
    
}
