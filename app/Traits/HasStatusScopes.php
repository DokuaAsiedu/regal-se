<?php

namespace App\Traits;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;

trait HasStatusScopes
{
    public function scopeWithStatus(Builder $query, Status $status)
    {
        return $query->whereHas('status', fn ($q) => $q->where('code', $status->value));
    }

    public function scopeActive(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::active);
    }

    public function scopeInActive(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::inActive);
    }

    public function scopePending(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::pending);
    }

    public function scopeApproved(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::approved);
    }

    public function scopeCompleted(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::completed);
    }

    public function scopeDeclined(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::declined);
    }

    public function scopeRejected(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::rejected);
    }

    public function scopeSuspended(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::suspended);
    }

    public function scopeSuccess(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::success);
    }

    public function scopeFailed(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::failed);
    }

    public function scopeAbandoned(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::abandoned);
    }

    public function scopeCancelled(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::cancelled);
    }

    public function scopeInvalid(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::invalid);
    }

    public function scopeUnknown(Builder $query)
    {
        return $this->scopeWithStatus($query, Status::unknown);
    }
}
