<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Category;
use App\Models\Status;
use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function find($id)
    {
        return $this->categoryRepository->find($id);
    }

    public function products()
    {
        return $this->categoryRepository->allQuery();
    }

    public function store($input)
    {
        if (empty($input['code'])) {
            $input['code'] = $this->generateCode($input['code']);
        } else {
            $this->checkIfCodeExists($input['code']);
        }

        $this->checkIfNameExists($input['name']);

        return $this->categoryRepository->create($input);
    }

    public function update($id, $input)
    {
        if (empty($input['code'])) {
            $input['code'] = $this->generateCode($input['code']);
        } else {
            $this->checkIfCodeExists($input['code'], $id);
        }

        $this->checkIfNameExists($input['name'], $id);

        return $this->categoryRepository->update($input, $id);
    }

    public function delete($ids)
    {
        Category::destroy($ids);
    }

    public function validStatuses()
    {
        return Status::whereIn('code', ['active', 'inactive'])
            ->get();
    }

    public function generateCode($code)
    {
        $last_id = Category::latest('id')->first('id')->id ?? 0;
        do {
            $padded_id = str_pad($last_id, 5, '0', STR_PAD_LEFT);
            $code = "CAT-$padded_id";
            $last_id++;
        } while (Category::where('code', $code)->exists());

        return $code;
    }

    public function checkIfCodeExists($code, $id = null)
    {
        $exists = $this->products()
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->where('code', $code)
            ->exists();

        if ($exists) {
            throw new CustomException('Category code already exists');
        }
    }

    public function checkIfNameExists($name, $id = null)
    {
        $exists = $this->products()
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->where('name', $name)
            ->exists();

        if ($exists) {
            throw new CustomException('Category name already exists');
        }
    }
}
