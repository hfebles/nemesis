<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingEntries extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_moves_account',
        'id_ledger_account',
        'description_accounting_entries',
        'date_accounting_entries',
        'amount_debe_accounting_entries',
        'amount_haber_accounting_entries',

    ];

    protected $primaryKey = 'id_moves_account';
}
