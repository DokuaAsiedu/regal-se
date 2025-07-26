<?php

namespace App\Livewire\Tables\Admin\KYC;

use App\Models\KYCSubmission;
use Faker\Provider\bg_BG\PhoneNumber;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Number;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class KycSubmissionsTable extends PowerGridComponent
{
    public string $tableName = 'kyc-submissions-table-vdrabc-table';

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
        return KYCSubmission::query()
            ->orderByDesc('created_at')
            ->with('status');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('customer_name')
            ->add('customer_phone_formatted', function (KYCSubmission $model) {
                return formatPhone($model->customer_phone, $model->customer_phone_prefix) ?? 'N/A';
            })
            ->add('customer_phone')
            ->add('customer_phone_country_code')
            ->add('customer_email')
            ->add('customer_address')
            ->add('customer_ghana_card_number')
            ->add('customer_date_of_birth_formatted', fn (KYCSubmission $model) => Carbon::parse($model->customer_date_of_birth)->format('d/m/Y'))
            ->add('company_name')
            ->add('customer_current_position')
            ->add('company_phone_prefix')
            ->add('company_phone')
            ->add('company_phone_country_code')
            ->add('company_address')
            ->add('company_email')
            ->add('customer_employment_start_date')
            ->add('status_name', function ($row) {
                return Blade::render('components.status', ['status' => $row->status]);
            })
            ->add('user_id')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Customer name', 'customer_name')
                ->sortable()
                ->searchable(),

            Column::make('Customer phone', 'customer_phone_formatted', 'customer_phone')
                ->sortable()
                ->searchable(),

            Column::make('Customer address', 'customer_address')
                ->sortable()
                ->searchable(),

            Column::make('Customer ghana card number', 'customer_ghana_card_number')
                ->sortable()
                ->searchable(),

            Column::make('Company name', 'company_name')
                ->sortable()
                ->searchable(),

            Column::make('Customer current position', 'customer_current_position')
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
            // Filter::datepicker('customer_date_of_birth'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('admin.kyc.table.columns.actions', ['row' => $row]);
    }
}
