<?php

namespace App\Repositories\Contracts;

use App\Data\ProductFilterData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getPaginated(ProductFilterData $filters): LengthAwarePaginator;

}
