<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_sub_group ', 'id_group', 'code_sub_group', 'name_sub_group'
    ];

    protected $primaryKey = 'id_sub_group';
}
