<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Product;
use App\Models\Status;
use App\Repositories\ProductRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductService
{
    protected $productRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function find($id)
    {
        return $this->productRepository->find($id);
    }

    public function all()
    {
        return $this->productRepository->all();
    }

    public function store($input)
    {
        if (empty($input['code'])) {
            $input['code'] = $this->generateCode($input['code']);
        } else {
            $this->checkIfCodeExists($input['code']);
        }

        $this->checkIfNameExists($input['name']);

        $product = $this->productRepository->create($input);

        if (isset($input['product_categories'])) {
            // $category_ids = array_map((fn ($item) => $item['id']), $input['product_categories']);
            $product->categories()->attach($input['product_categories']);
        }

        if (isset($input['product_images'])) {
            foreach ($input['product_images'] as $image) {
                $product->addMedia($image)
                    ->toMediaCollection('product_images');
            }
        }

        return $product;
    }

    public function update($id, $input)
    {
        if (empty($input['code'])) {
            $input['code'] = $this->generateCode($input['code']);
        } else {
            $this->checkIfCodeExists($input['code'], $id);
        }

        $this->checkIfNameExists($input['name'], $id);

        $product = $this->productRepository->update($input, $id);

        if (isset($input['product_categories'])) {
            $category_ids = array_filter($input['product_categories'], fn ($item) => $item);
            $product->categories()->sync($category_ids);
        }

        if (isset($input['product_images'])) {
            foreach ($input['product_images'] as $image) {
                $product->addMedia($image)
                    ->toMediaCollection('product_images');
            }
        }

        if (isset($input['deleted_image_ids'])) {
            Media::destroy($input['deleted_image_ids']);
        }

        return $product;
    }

    public function delete($ids)
    {
        $this->productRepository->delete($ids);
    }

    public function allQuery($search = [])
    {
        return $this->productRepository->allQuery($search);
    }

    public function validStatuses()
    {
        return Status::whereIn('code', ['active', 'inactive'])
            ->get();
    }

    public function generateCode($code)
    {
        $last_id = Product::latest('id')->first('id')->id ?? 0;
        do {
            $padded_id = str_pad($last_id, 5, '0', STR_PAD_LEFT);
            $code = "PRD-$padded_id";
            $last_id++;
        } while (Product::where('code', $code)->exists());

        return $code;
    }

    public function checkIfCodeExists($code, $id = null)
    {
        $exists = $this->allQuery()
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->where('code', $code)
            ->exists();

        if ($exists) {
            throw new CustomException('Product code already exists');
        }
    }

    public function checkIfNameExists($name, $id = null)
    {
        $exists = $this->allQuery()
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->where('name', $name)
            ->exists();

        if ($exists) {
            throw new CustomException('Product name already exists');
        }
    }
}
