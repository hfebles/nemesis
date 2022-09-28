<?php

namespace App\Models\Conf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = ['id_tax', 'name_tax', 'amount_tax', 'code_sub_ledger_account', 'billable_tax'];

    protected $primaryKey = 'id_tax';
}
