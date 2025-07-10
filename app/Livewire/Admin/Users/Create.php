<?php

namespace App\Livewire\Admin\Users;

use App\Services\UserService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use HandlesErrorMessage;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $status = "";

    public $id;
    public $user;

    public $header;
    public $success_message;
    public $error_message;

    protected $userService;

    public function rules() {
        $state = isset($this->id) ? 'nullable' : 'required';

        return [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'status' => 'required|exists:status,id',
            'password' => [
                $state,
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    public function mount($id = null)
    {
        try {
            $this->loadData($id);
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            toastr()->error(__('Error mounting component') . ': '. $message);
        }
    }

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Computed()]
    public function validStatuses()
    {
        return $this->userService->validStatuses();
    }

    public function loadData($id)
    {
        if (isset($id)) {
            $this->user = $this->userService->find($id);
            $this->name = $this->user->name ?? '';
            $this->email = $this->user->email ?? '';
            $this->status = $this->user->status->id ?? '';

            $this->header = __('Edit User');
            $this->success_message = __('User successfully updated');
        } else {
            $this->header = __('Create New User');
            $this->success_message = __('User successfully created');
        }
    }

    public function save()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $payload = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'status_id' => $this->status,
            ];
            if (isset($this->id)) {
                $this->userService->update($this->id, $payload);
            } else {
                $this->userService->store($payload);
            }
            DB::commit();
            toastr()->success($this->success_message);
            return redirect()->route('users.index');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            toastr()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }
}
