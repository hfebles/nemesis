<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_group', 'code_group', 'name_group','id_type_ledger_account'
    ];

    protected $primaryKey = 'id_group';
}
