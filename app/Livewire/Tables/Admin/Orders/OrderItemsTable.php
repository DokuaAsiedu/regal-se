<?php

namespace App\Livewire\Tables\Admin\Orders;

use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class OrderItemsTable extends PowerGridComponent
{
    public string $tableName = 'order-items-table-neaaut-table';

    public int $order_id;

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
        return OrderItem::query()
            ->where('order_id', $this->order_id);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('product_name', function ($row) {
                return $row->product->name;
            })
            ->add('quantity')
            ->add('unit_price')
            ->add('payment', function ($row) {
                return $row->payment_plan->value;
            })
            ->add('down_payment_percentage')
            ->add('down_payment_amount')
            ->add('installment_months')
            ->add('installment_amount')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Product', 'product_name'),

            Column::make('Quantity', 'quantity')
                ->sortable()
                ->searchable(),

            Column::make('Unit price', 'unit_price')
                ->sortable()
                ->searchable(),

            Column::make('Payment plan', 'payment'),

            Column::make('Down payment percentage', 'down_payment_percentage')
                ->sortable()
                ->searchable(),

            Column::make('Down payment amount', 'down_payment_amount')
                ->sortable()
                ->searchable(),

            Column::make('Installment months', 'installment_months')
                ->sortable()
                ->searchable(),

            Column::make('Installment amount', 'installment_amount')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }
}
