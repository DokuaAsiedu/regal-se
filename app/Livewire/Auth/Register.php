<?php

namespace App\Livewire\Auth;

use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    private $roleService;

    public function boot(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $status_id = Status::where('code', 'active')->first()->id ?? null;
        $admin_role_id = $this->roleService
            ->adminRole()
            ->id ?? null;

        $input = array_merge($validated, [
            'status_id' => $status_id,
            'role_id' => $admin_role_id,
        ]);

        event(new Registered(($user = User::create($input))));

        Auth::login($user);

        $this->redirect(route('home', absolute: false), navigate: true);
    }
}
