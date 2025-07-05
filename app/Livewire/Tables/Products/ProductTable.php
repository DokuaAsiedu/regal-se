<?php

namespace App\Livewire\Tables\Products;

use App\Models\Product;
use Flux\Flux;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Header;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ProductTable extends PowerGridComponent
{
    public string $tableName = 'product-table-myz085-table';
    public bool $deferLoading = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('products.actions.index'),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Product::query();
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
            ->add('cost_price')
            ->add('selling_price')
            ->add('quantity')
            ->add('description')
            ->add('status_name', function ($row) {
                return Blade::render('status', ['status' => $row->status]);
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Cost Price', 'cost_price')
                ->sortable()
                ->searchable(),

            Column::make('Selling Price', 'selling_price')
                ->sortable()
                ->searchable(),

            Column::make('Quantity', 'quantity')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
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
        return view('products.columns.actions', ['row' => $row]);
    }
}
