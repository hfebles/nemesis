<?php

namespace App\Models\Conf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    
    use HasFactory;

    protected $primaryKey = 'id_zone';

    protected $fillable = ['name_zone', 'ids_estados', 'enabled_zone'];
}
