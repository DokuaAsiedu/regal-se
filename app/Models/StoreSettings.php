<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSettings extends Model
{
    protected $fillable = [
        'name',
        'code',
        'value',
    ];
}
