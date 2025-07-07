<?php

namespace App\Livewire\Tables\Users;

use App\Models\User;
use App\Services\UserService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UsersTable extends PowerGridComponent
{
    use HandlesErrorMessage;

    public string $tableName = 'users-table-sycnew-table';

    protected $listeners = ['refresh' => '$refresh'];

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('users.table.header.actions'),
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
                return Blade::render('status', ['status' => $row->status]);
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

    public function showDeleteModal($id): void
    {
        $this->dispatch('openModal', component: 'components.delete-confirmation-modal', arguments: [ 
            'ids' =>  [$id],
            'class' => UserService::class,
        ]);
    }

    public function actionsFromView($row): View
    {
        return view('users.table.columns.actions', ['row' => $row]);
    }
}
