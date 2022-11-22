<?php

namespace App\Models\Conf\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    use HasFactory;


    protected $primaryKey = 'id_estado';

    public static function statesPlucks()
    {
        return Estados::pluck('estado', 'id_estado');   
    }

}



/**9cCZgtMhjKyBwhc */ nemesis

/**t4kZk3L3Us2BCHU */ rj