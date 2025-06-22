<?php

namespace App\Services;

use App\Models\Product;
use App\Data\ProductData;
use App\Data\ProductFilterData;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProductSearchRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected ProductSearchRepositoryInterface $productSearchRepository
    )
    {
    }
    public function createProduct(ProductData $data): Product
    {
        $product = Product::create($data->all());
        $product->load('category');

        $this->productSearchRepository->index($product);

        return $product;
    }

    public function updateProduct(Product $product, ProductData $data): bool
    {
        $result = $product->update($data->toArray());
        $product->load('category');

        $this->productSearchRepository->index($product->fresh());

        return $result;
    }

    public function deleteProduct(Product $product): ?bool
    {
        $this->productSearchRepository->delete($product);

        return $product->delete();
    }

    public function getProducts(ProductFilterData $filters): LengthAwarePaginator
    {
        if ($filters->searchTerm) {
            $results = $this->productSearchRepository->search($filters->searchTerm);
            return new \Illuminate\Pagination\LengthAwarePaginator($results, $results->count(), 10);
        }

        return $this->productRepository->getPaginated($filters);
    }

}
