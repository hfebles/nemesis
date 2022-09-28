<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeLedgerAccounts extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_type_ledger_account';
}
