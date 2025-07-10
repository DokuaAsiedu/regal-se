<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([ActiveScope::class])]
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'cost_price',
        'selling_price',
        'quantity',
        'description',
        'status_id',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
