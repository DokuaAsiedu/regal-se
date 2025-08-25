<?php

namespace App\View\Composers;

use App\Services\CategoryService;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class CategoryComposer
{
    public $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $current_url = Request::url() . ltrim(Request::getRequestUri(), '/');
        $categories = $this->categoryService
            ->allQuery()
            ->active()
            ->get()
            ->map(function ($item) use ($current_url) {
                $category_route = route('home', ['category_code' => $item->code]);
                $is_current = $current_url == $category_route;
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'href' => $category_route,
                    'is_current' => $is_current,
                ];
            });
        $min_category_number = 3;

        $data = [
            'categories' => $categories,
            'min_category_number' => $min_category_number,
        ];
        $view->with($data);
    }
}