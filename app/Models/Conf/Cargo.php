<?php

namespace App\Models\Conf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cargo';

    protected $fillable = ['id_zone', 'price_cargo'];
}
