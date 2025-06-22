<?php

namespace App\Data;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Rule;

class ProductData extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public readonly string $title,

        #[Rule(['required', 'numeric', 'min:0'])]
        public readonly float $price,

        #[Rule(['required', 'integer', 'exists:categories,id'])]
        public readonly int $category_id,

        #[Rule(['nullable', 'string'])]
        public readonly ?string $description,

        #[Rule(['nullable', 'string', 'max:255'])]
        public readonly ?string $image,
    ) {}
}
