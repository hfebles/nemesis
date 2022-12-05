<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithholdingIvaSales extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_withholding_iva_sale';

    protected $fillable = [
        'voucher_number_whs',
        'type_wh',
        'date_whs',
        'state_wh',
        'amount_tax_invoice_whs',
        'amount_base_imponible_whs',
        'amount_tax_retention_whs',
        'id_client',
        'id_invoice',
    ];
}
