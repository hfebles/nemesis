<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubLedgerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_sub_ledger_account ', 'id_ledger_account', 'code_sub_ledger_account', 'name_sub_ledger_account'
    ];

    protected $primaryKey = 'id_sub_ledger_account';
}
