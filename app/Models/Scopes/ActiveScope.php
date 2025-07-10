<?php

namespace App\Models\Scopes;

use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{
    protected static $active_status_id;
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (!isset(self::$active_status_id)) {
            self::$active_status_id = Status::where('code', 'active')
                ->value('id');
        }

        if (self::$active_status_id) {
            $builder->where('status_id', self::$active_status_id);
        }
    }
}
