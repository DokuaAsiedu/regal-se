<?php

namespace App\Livewire\Tables\Admin\Companies;

use App\Models\CompanyStaff;
use App\Services\CompanyStaffService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

final class StaffTable extends PowerGridComponent
{
    use HandlesErrorMessage;

    public string $tableName = 'staff-bygid3-table';

    protected $listeners = ['companyUpdated', 'refresh' => '$refresh'];

    public $company_id;

    protected $companyStaffService;

    public function boot(CompanyStaffService $companyStaffService)
    {
        $this->companyStaffService = $companyStaffService;
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return CompanyStaff::query()
            ->when($this->company_id, function ($query, $company_id) {
                return $query->where('company_id', $company_id);
            })
            ->orderByDesc('created_at');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('staff_id')
            ->add('ghana_card_number')
            ->add('company', function (CompanyStaff $model) {
                return $model->company->name ?? 'N/A';
            })
            ->add('user', function (CompanyStaff $model) {
                return $model->user->name ?? 'N/A';
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Staff id', 'staff_id')
                ->sortable()
                ->searchable(),

            Column::make('Ghana card number', 'ghana_card_number')
                ->sortable()
                ->searchable(),

            Column::make('Company', 'company'),
            Column::make('User', 'user'),

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
        return view('admin.company-staff.table.columns.actions', ['row' => $row]);
    }

    public function showDeleteModal($id): void
    {
        $this->dispatch('openModal', component: 'components.confirmation-modal', arguments: [
            'event' => 'delete-staff',
            'id' =>  $id,
            'confirmText' => __('Delete'),
        ]);
    }

    #[On('delete-staff')]
    public function deleteStaff($id)
    {
        try {
            DB::beginTransaction();
            $this->companyStaffService->delete([$id]);
            DB::commit();
            $message = 'Successfully deleted employee';
            flash()->success($message);
            $this->dispatch('refresh');
            $this->dispatch('closeModal');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            flash()->error($message);
        }
    }

    public function companyUpdated($company_id)
    {
        $this->company_id = $company_id;
    }
}
