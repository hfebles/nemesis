<?php

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupWorkers extends Model
{
    use HasFactory;


    protected $fillable = [
        'name_group_worker',
    ];

    protected $primaryKey = 'id_group_worker';
}
