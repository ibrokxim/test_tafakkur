<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Rule;

class RegisterData extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public readonly string $name,

        #[Rule(['required', 'string', 'email', 'max:255', 'unique:users'])]
        public readonly string $email,

        #[Rule(['required', 'string', 'min:6'])]
        public readonly string $password,
    ) {}
}
