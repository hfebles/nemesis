<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_ledger_account ', 'id_sub_group', 'code_ledger_account', 'name_ledger_account'
    ];

    protected $primaryKey = 'id_ledger_account';
}
