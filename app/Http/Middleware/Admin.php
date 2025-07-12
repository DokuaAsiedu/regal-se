<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Services\RoleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return abort(401);
        }

        $admin_role = $this->roleService->adminRole();

        if (!$admin_role) {
            return abort(500, 'Admin role not configured.');
        }

        if (Auth::user()->role_id != $admin_role->id) {
            return abort(401);
        }

        return $next($request);
    }
}
