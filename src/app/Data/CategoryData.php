<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CategoryData extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public readonly string $title,

        #[Rule(['string'])]
        public readonly ?string $description,

        #[Rule(['nullable', 'string', 'max:255'])]
        public readonly ?string $image,
    ) {}
}
