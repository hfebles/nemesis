<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_product',
        'description_product',
        'price_product',
        'product_usd_product',
        'tax_exempt_product',
        'qty_product',
        'salable_product',
        'code_product',
        'part_number_product',
        'lot_number_product',
        'id_warehouse',
        'id_product_category',
        'id_unit_product',
        'id_presentation_product',
    ];

    protected $primaryKey = 'id_product';
}
