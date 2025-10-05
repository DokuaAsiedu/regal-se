<?php

namespace App\Models;

use App\Traits\HasStatusScopes;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KYC extends Model
{
    use LogsActivity, HasStatusScopes;

    protected $table = 'kycs';

    protected $fillable = [
        'name',
        'phone_prefix',
        'phone',
        'phone_country_code',
        'email',
        'address',
        'ghana_card_number',
        'date_of_birth',
        'current_position',
        'employment_start_date',
        'staff_id',
        'company_id',
        'status_id',
        'user_id',
        'reviewed_by',
        'rejection_reason',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class);
    }
}
