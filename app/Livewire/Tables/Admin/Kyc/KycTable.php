<?php

namespace App\Livewire\Tables\Admin\Kyc;

use App\Models\Company;
use App\Models\KYC;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class KycTable extends PowerGridComponent
{
    public string $tableName = 'kyc-table-ovdrta-table';

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
        return KYC::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('phone_formatted', function (KYC $model) {
                return formatPhone($model->phone, $model->phone_prefix) ?? 'N/A';
            })
            ->add('email')
            ->add('address')
            ->add('ghana_card_number')
            ->add('current_position')
            ->add('employment_start_date_formatted', fn (KYC $model) => formatDate($model->employment_start_date, 'jS F, Y'))
            ->add('staff_id')
            ->add('company', function (KYC $model) {
                return $model->company->name ?? 'N/A';
            })
            ->add('status_name', function ($row) {
                return Blade::render('components.status', ['status' => $row->status]);
            })
            ->add('user_name', function (KYC $model) {
                return $model->user->name ?? 'N/A';
            })
            ->add('reviewer', function (KYC $model) {
                return $model->reviewedBy->name ?? 'N/A';
            })
            ->add('rejection_reason')
            ->add('created_at_formatted', fn ( $model) => formatDate($model->created_at, 'jS F, Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone_formatted', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Address', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Ghana card number', 'ghana_card_number')
                ->sortable()
                ->searchable(),

            Column::make('Current position', 'current_position')
                ->sortable()
                ->searchable(),

            Column::make('Employment start date', 'employment_start_date_formatted')
                ->sortable()
                ->searchable(),

            Column::make('Staff id', 'staff_id')
                ->sortable()
                ->searchable(),
            Column::make('User', 'user_name'),
            Column::make('Status', 'status_name'),
            Column::make('Reviewed by', 'reviewed_er'),
            Column::make('Rejection reason', 'rejection_reason')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('date_of_birth'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('admin.kyc.table.columns.actions', ['row' => $row]);
    }
}
