<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Data\ProductData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Data\ProductFilterData;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected ProductRepositoryInterface $productRepository)
    { }

    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = ProductFilterData::from([
            'searchTerm' => $request->input('search')
        ]);

        $products = $this->productService->getProducts($filters);

        return ProductResource::collection($products);
    }

    public function store(ProductData $productData): ProductResource
    {
        $product = $this->productService->createProduct($productData);

        return new ProductResource($product);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->loadMissing('category'));
    }

    public function update(ProductData $productData, Product $product): ProductResource
    {
        $this->productService->updateProduct($product, $productData);
        return new ProductResource($product->fresh()->loadMissing('category'));
    }

    public function destroy(Product $product): Response
    {
        $this->productService->deleteProduct($product);

        return response()->noContent();
    }
}
