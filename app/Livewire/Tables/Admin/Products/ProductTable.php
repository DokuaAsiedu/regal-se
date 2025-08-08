<?php

namespace App\Livewire\Tables\Admin\Products;

use App\Models\Product;
use App\Services\ProductService;
use App\Traits\HandlesErrorMessage;
use Flux\Flux;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Header;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Throwable;

final class ProductTable extends PowerGridComponent
{
    use HandlesErrorMessage;

    public string $tableName = 'product-table-myz085-table';
    public bool $deferLoading = true;

    protected $listeners = ['refresh' => '$refresh'];

    protected $productService;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('admin.products.table.header.actions'),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
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
                return Blade::render('components.status', ['status' => $row->status]);
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

    #[On('delete-product')]
    public function deleteProduct($id)
    {
        try {
            DB::beginTransaction();
            $this->productService->delete([$id]);
            DB::commit();
            $message = 'Successfully deleted product';
            flash()->success($message);
            $this->dispatch('refresh');
            $this->dispatch('closeModal');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            flash()->error($message);
        }
    }

    public function showDeleteModal($id): void
    {
        $this->dispatch('openModal', component: 'components.confirmation-modal', arguments: [ 
            'event' => 'delete-product',
            'id' =>  $id,
            'confirmText' => __('Delete'),
        ]);
    }

    public function actionsFromView($row): View
    {
        return view('admin.products.table.columns.actions', ['row' => $row]);
    }
}
