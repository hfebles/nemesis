<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoicing extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_invoicing';

    protected $fillable = [
        'ref_name_invoicing ',
        'ctrl_num_invoicing', 
        'ctrl_num', 
        'total_amount_invoicing',
        'exempt_amout_invoicing',
        'no_exempt_amout_invoicing',
        'total_amount_tax_invoicing',
        'residual_amount_invoicing',
        'date_invoicing',
        'type_payment',
        'id_exchange',
        'id_order_state',
        'id_company',
        'id_client',
        'id_user',
        'id_worker',
        'id_delivery',
        'state_delivery',
    ];
}
