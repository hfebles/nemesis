<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingEntries extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_accounting_entries',
        'amount_accounting_entries',
        'id_ledger_account',
        'description_accounting_entries',
        'id_moves_account',
        'id_invocing',

    ];

    protected $primaryKey = 'id_accounting_entries';
}
