<?php

namespace App\Services;

use App\Data\CategoryData;
use App\Models\Category;
use App\Repositories\Contracts\CategorySearchRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(protected CategorySearchRepositoryInterface $searchRepository)
    {
    }

    public function getCategories(string $searchTerm = null): LengthAwarePaginator
    {
        if ($searchTerm) {
            $results = $this->searchRepository->search($searchTerm);
            return new LengthAwarePaginator($results, $results->count(), 15);
        }

        return Category::oldest()->paginate(15);
    }

    public function createCategory(CategoryData $data): Category
    {
        $category = Category::create($data->all());
        $this->searchRepository->index($category);
        return $category;
    }

    public function updateCategory(Category $category, CategoryData $data): bool
    {
        $result = $category->update($data->toArray());
        $this->searchRepository->index($category->fresh());
        return $result;
    }

    public function deleteCategory(Category $category): ?bool
    {
        $this->searchRepository->delete($category);
        return $category->delete();
    }

}
