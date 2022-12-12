<?php

namespace App\Models\Conf\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicingConfigutarion extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_invoicing_configutarion';

    protected $fillable = [
        'print_name_invoicing_configutarion ', 
        'correlative_invoicing_configutarion', 
        'control_number_invoicing_configutarion', 
        'id_ledger_account',
        'type_Ledger'
    ];
}
