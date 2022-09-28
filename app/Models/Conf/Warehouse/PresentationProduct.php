<?php

namespace App\Models\Conf\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentationProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_presentation_product',
        'enabled_presentation_product',
    ];

    protected $primaryKey = 'id_presentation_product';

}
