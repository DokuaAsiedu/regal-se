<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'code',
        'name',
        'cost_price',
        'selling_price',
        'quantity',
        'description',
        'status_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function scopeActive(Builder $query)
    {
        $active_status_id = Status::where('code', 'active')
            ->value('id');

        return $query->where('status_id', $active_status_id);
    }

    #[Scope]
    public function scopeQuantityAvailable(Builder $query)
    {
        return $query->where('quantity', '>', '0');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->withTimestamps();
    }
}
