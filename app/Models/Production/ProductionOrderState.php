<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrderState extends Model
{
    use HasFactory;

    protected $fillable = ['name_production_order_state'];

    protected $primaryKey = 'id_production_order_state';
}
