<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetails extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_purchase_order_detail';
}
