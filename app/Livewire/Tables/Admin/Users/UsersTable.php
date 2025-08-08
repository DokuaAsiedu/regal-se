<?php

namespace App\Livewire\Tables\Admin\Users;

use App\Models\User;
use App\Services\UserService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Throwable;

final class UsersTable extends PowerGridComponent
{
    use HandlesErrorMessage;

    public string $tableName = 'users-table-sycnew-table';

    protected $listeners = ['refresh' => '$refresh'];

    protected $userService;

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('admin.users.table.header.actions'),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name', function ($row) {
                $name = $row->name;
                if (Auth::check() && Auth::user()->id == $row->id) {
                    $attributes = new ComponentAttributeBag([
                        'variant' => 'pill',
                        'color' => 'red',
                        'style' => 'font-size: 0.6rem;',
                    ]);
                    $self = view('components.badge', [
                        'name' => 'You',
                        'attributes' => $attributes,
                    ])->render();
                }

                return isset($self) ? "$name  $self" : $name;
            })
            ->add('email')
            ->add('status_name', function ($row) {
                return Blade::render('components.status', ['status' => $row->status]);
            })
            ->add('role_name', function ($row) {
                return $row->role->name ?? 'N/A';
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable()
                ->bodyAttribute('flex items-center gap-5'),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Role', 'role_name')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status_name'),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Actions')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[On('delete-user')]
    public function deleteUser($id)
    {
        try {
            DB::beginTransaction();
            $this->userService->delete([$id]);
            DB::commit();
            $message = 'Successfully deleted user';
            flash()->success($message);
            $this->dispatch('refresh');
            $this->dispatch('closeModal');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            flash()->error($message);
        }
    }

    public function showDeleteModal($id): void
    {
        $this->dispatch('openModal', component: 'components.confirmation-modal', arguments: [ 
            'event' => 'delete-user',
            'id' =>  $id,
            'confirmText' => __('Delete'),
        ]);
    }

    public function actionsFromView($row): View
    {
        return view('admin.users.table.columns.actions', ['row' => $row]);
    }
}
