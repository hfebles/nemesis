<?php

namespace App\Models\Conf\Purchases;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderConfig extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_purchase_order_config';

    protected $fillable = [
        'control_number_purchase_order_config',
    ];
}
