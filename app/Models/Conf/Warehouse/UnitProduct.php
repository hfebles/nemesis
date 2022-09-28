<?php

namespace App\Models\Conf\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_unit_product',
        'short_unit_product',
        'enabled_unit_product',
    ];

    protected $primaryKey = 'id_unit_product';
}
