<?php

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workers extends Model
{
    use HasFactory;

    protected $fillable = [
        'firts_name_worker', 'last_name_worker', 'dni_worker', 'phone_worker', 'mail_worker', 'id_group_worker', 'id_user', 
    ];

    
    protected $primaryKey = 'id_worker';



    
}
