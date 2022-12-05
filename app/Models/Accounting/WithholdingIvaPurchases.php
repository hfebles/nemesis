<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithholdingIvaPurchases extends Model
{
    use HasFactory;


    protected $primaryKey = 'id_withholding_iva_purchase';

    protected $fillable = [
        'voucher_number_whp',
        'voucher_control_whp',
        'type_wh',
        'date_whp',
        'state_wh',
        'amount_tax_invoice_whp',
        'amount_base_imponible_whp',
        'amount_tax_retention_whp',
        'id_supplier',
        'id_purchase',
    ];
}
