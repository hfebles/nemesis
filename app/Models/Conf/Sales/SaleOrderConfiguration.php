<?php

namespace App\Models\Conf\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderConfiguration extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sale_order_configuration';
}
