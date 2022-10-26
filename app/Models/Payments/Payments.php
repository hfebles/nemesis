<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_payment';

    protected $fillable = ['id_client', 'id_invoice', 'id_delivery_note', 'type_pay', 'amount_payment', 'ref_payment', 'date_payment', 'id_bank',];
}
