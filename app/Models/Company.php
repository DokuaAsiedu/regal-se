<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasStatusScopes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Company extends Model
{
    use LogsActivity, HasStatusScopes;

    protected $fillable = [
        'code',
        'name',
        'email',
        'address',
        'phone_prefix',
        'phone',
        'phone_country_code',
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
}
