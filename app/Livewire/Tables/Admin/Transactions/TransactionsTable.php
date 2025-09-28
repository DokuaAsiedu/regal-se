<?php

namespace App\Livewire\Tables\Admin\Transactions;

use App\Models\Transaction;
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

final class TransactionsTable extends PowerGridComponent
{
    public string $tableName = 'transactions-table-zuphqr-table';

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
        return Transaction::query()
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
            ->add('payment_id')
            ->add('amount')
            ->add('currency')
            ->add('authorization_url')
            ->add('reference')
            ->add('gateway')
            ->add('status_id')
            ->add('status_name', function ($row) {
                return Blade::render('components.status', ['status' => $row->status]);
            })
            ->add('channel')
            ->add('transaction_id')
            ->add('payload')
            ->add('paid_at')
            ->add('processed_at')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Amount', 'amount')
                ->sortable()
                ->searchable(),

            Column::make('Gateway', 'gateway')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status_name'),
            Column::make('Channel', 'channel')
                ->sortable()
                ->searchable(),

            Column::make('Paid at', 'paid_at')
                ->sortable()
                ->searchable(),

            Column::make('Processed at', 'processed_at')
                ->sortable()
                ->searchable(),

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
        return view('admin.transactions.table.columns.actions', ['row' => $row]);
    }
}
