<?php

namespace App\Models\Conf\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_product_category',
        'enabled_product_category',
    ];

    protected $primaryKey = 'id_product_category';
}
