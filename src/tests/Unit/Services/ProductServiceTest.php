<?php

namespace Tests\Unit\Services;

use App\Data\ProductFilterData;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    public function test_get_products_calls_repository_with_filters(): void
    {
        $repositoryMock = Mockery::mock(ProductRepositoryInterface::class);

        $filters = new ProductFilterData(searchTerm: 'test');

        $repositoryMock
            ->shouldReceive('getPaginated')
            ->once()
            ->with($filters)
            ->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $service = new ProductService($repositoryMock);

        $service->getProducts($filters);

    }
}
