<?php

namespace App\Repositories;

use App\Models\Product;
use App\Data\ProductFilterData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getPaginated(ProductFilterData $filters): LengthAwarePaginator
    {
        if ($filters->searchTerm) {
            return Product::search($filters->searchTerm)
                ->query(fn ($query) => $query->with('category'))
                ->paginate(10);
        }

        return Product::with('category')->latest()->paginate(10);
    }
}
