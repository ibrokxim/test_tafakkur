<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Data\CategoryData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    )
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = $this->categoryService->getCategories(
            $request->input('search')
        );
        return CategoryResource::collection($categories);
    }

    public function store(CategoryData $categoryData): CategoryResource
    {
        $category = $this->categoryService->createCategory($categoryData);
        return new CategoryResource($category);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    public function update(CategoryData $categoryData, Category $category): CategoryResource
    {
        $this->categoryService->updateCategory($category, $categoryData);
        return new CategoryResource($category->fresh());
    }

    public function destroy(Category $category): Response
    {
        $this->categoryService->deleteCategory($category);
        return response()->noContent();
    }
}
