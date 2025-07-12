<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([ActiveScope::class])]
class Role extends Model
{
    protected $fillable = [
        'code',
        'name',
        'status_id',
    ];
}
