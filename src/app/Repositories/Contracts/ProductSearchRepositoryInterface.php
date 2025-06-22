<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductSearchRepositoryInterface
{
    public function search(string $query): Collection;
    public function index(Product $product): void;
    public function delete(Product $product): void;

}
