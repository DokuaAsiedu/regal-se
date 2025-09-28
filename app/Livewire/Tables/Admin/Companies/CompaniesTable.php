<?php

namespace App\Livewire\Tables\Admin\Companies;

use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Throwable;

final class CompaniesTable extends PowerGridComponent
{
    public string $tableName = 'companies-table-d4vwae-table';

    protected $listeners = ['refresh' => '$refresh'];

    protected $companyService;

    public function boot(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('admin.companies.table.header.actions'),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Company::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('code')
            ->add('name')
            ->add('email')
            ->add('address')
            ->add('phone_formatted', function (Company $model) {
                return formatPhone($model->phone, $model->phone_prefix) ?? 'N/A';
            })
            ->add('status_name', function ($row) {
                return Blade::render('components.status', ['status' => $row->status]);
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Address', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone_formatted', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status_name'),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    public function actionsFromView($row): View
    {
        return view('admin.companies.table.columns.actions', ['row' => $row]);
    }

    public function showDeleteModal($id): void
    {
        $this->dispatch('openModal', component: 'components.confirmation-modal', arguments: [
            'event' => 'delete-company',
            'id' =>  $id,
            'confirmText' => __('Delete'),
        ]);
    }

    #[On('delete-company')]
    public function deleteCategory($id)
    {
        try {
            DB::beginTransaction();
            $this->companyService->delete([$id]);
            DB::commit();
            $message = 'Successfully deleted company';
            flash()->success($message);
            $this->dispatch('refresh');
            $this->dispatch('closeModal');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            flash()->error($message);
        }
    }
}
