<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ProductFilterData extends Data
{
    public function __construct(
        public readonly ?string $searchTerm,
    )
    {
    }
}
