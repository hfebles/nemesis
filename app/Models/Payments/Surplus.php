<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surplus extends Model
{
    use HasFactory;


    protected $primaryKey = 'id_surplus';

    protected $fillable = ['amount_surplus', 'id_payment', 'id_client'];
}
