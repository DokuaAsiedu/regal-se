<?php

namespace App\Livewire\Tables\Admin\Categories;

use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\HandlesErrorMessage;
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

final class CategoryTable extends PowerGridComponent
{
    use HandlesErrorMessage;

    public string $tableName = 'category-table-xipkin-table';

    protected $listeners = ['refresh' => '$refresh'];

    protected $categoryService;

    public function boot(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('admin.categories.table.header.actions'),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Category::query();
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
            ->add('description')
            ->add('status_name', function ($row) {
                return Blade::render('components.status', ['status' => $row->status]);
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name')
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

    public function showDeleteModal($id): void
    {
        $this->dispatch('openModal', component: 'components.delete-confirmation-modal', arguments: [ 
            'ids' =>  [$id],
            'class' => CategoryService::class,
        ]);
    }

    public function actionsFromView($row): View
    {
        return view('admin.categories.table.columns.actions', ['row' => $row]);
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
