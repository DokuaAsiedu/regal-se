<?php

namespace App\Livewire\Tables\Admin\Orders;

use App\Models\Order;
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

final class OrdersTable extends PowerGridComponent
{
    public string $tableName = 'orders-table-q3olxt-table';

    public function setUp(): array
    {
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
        return Order::query()
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
            ->add('code')
            ->add('status_name', function ($row) {
                return Blade::render('components.status', ['status' => $row->status]);
            })
            ->add('customer', function ($row) {
                return $row->user->name ?? $row->customer_name ?? 'N/A';
            })
            ->add('total_amount')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Customer', 'customer')
                ->sortable()
                ->searchable(),

            Column::make('Total amount', 'total_amount')
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
        return view('admin.orders.table.columns.actions', ['row' => $row]);
    }
}
