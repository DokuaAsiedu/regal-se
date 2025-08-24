<?php

namespace App\Models;

use App\Enums\Status as StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{
    use LogsActivity;

    protected $fillable = [
        'code',
        'name',
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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories')
            ->withTimestamps();
    }

    public function scopeActive()
    {
        return $this->whereHas('status', function ($query) {
            $query->where('code', StatusEnum::active->value);
        });
    }
}
