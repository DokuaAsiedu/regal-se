<?php

namespace App\Models;

use App\Enums\Status as StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KYCSubmission extends Model
{
    use LogsActivity;

    protected $table = 'kyc_submissions';

    protected $fillable = [
        'customer_name',
        'customer_phone_prefix',
        'customer_phone',
        'customer_phone_country_code',
        'customer_email',
        'customer_address',
        'customer_ghana_card_number',
        'customer_date_of_birth',
        'company_name',
        'customer_current_position',
        'company_phone_prefix',
        'company_phone',
        'company_phone_country_code',
        'company_address',
        'company_email',
        'customer_employment_start_date',
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

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeApproved($query)
    {
        return $query->whereHas('status', function ($query) {
            $query->where('code', StatusEnum::approved->value);
        });
    }
}
