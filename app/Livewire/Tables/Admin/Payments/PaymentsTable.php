<?php

namespace App\Livewire\Tables\Admin\Payments;

use App\Models\Payment;
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

final class PaymentsTable extends PowerGridComponent
{
    public string $tableName = 'payments-table-pqtxfk-table';

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
        return Payment::query()
            ->with(['status'])
            ->orderByDesc('created_at');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('payable_type')
            ->add('payable_id')
            ->add('amount_formatted', function (Payment $model) {
                return formatCurrency($model->amount, $model->currency);
            })
            ->add('status_name', function ($row) {
                return Blade::render('components.status', ['status' => $row->status]);
            })
            ->add('payment_channel', function (Payment $model) {
                return $model->payment_channel ?? 'N/A';
            })
            ->add('transaction_id', function (Payment $model) {
                return $model->transaction_id ?? 'N/A';
            })
            ->add('due_date_formatted', fn (Payment $model) => formatDate($model->due_date, 'jS F, Y'))
            ->add('paid_at_formatted', function (Payment $model) {
                return $model->paid_at ? formatDate($model->paid_at, 'M d Y, h:i a') : __('Not Paid');
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Linked To', 'payable_type')
                ->sortable()
                ->searchable(),

            Column::make('Amount', 'amount_formatted')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status_name'),
            Column::make('Payment channel', 'payment_channel')
                ->sortable()
                ->searchable(),

            Column::make('Transaction id', 'transaction_id'),
            Column::make('Due date', 'due_date_formatted', 'due_date')
                ->sortable(),

            Column::make('Paid at', 'paid_at_formatted', 'paid_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('due_date'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('admin.payments.table.columns.actions', ['row' => $row]);
    }
}
