<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia;

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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
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
