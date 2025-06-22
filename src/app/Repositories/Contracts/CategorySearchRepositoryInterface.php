<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategorySearchRepositoryInterface
{
    public function search(string $query): Collection;
    public function index(Category $category): void;
    public function delete(Category $category): void;
}
