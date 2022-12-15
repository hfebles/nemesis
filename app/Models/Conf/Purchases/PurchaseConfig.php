<?php

namespace App\Models\Conf\Purchases;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseConfig extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_purchase_config';
}
