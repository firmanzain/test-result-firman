<?php

namespace App\DTOs;

readonly class AuthDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?string $token = null,
        public ?string $errorMessage = null,
    )
    {
        //
    }

    public static function success(string $token): self
    {
        return new self(token: $token, errorMessage: null);
    }

    public static function failure(string $errorMessage): self
    {
        return new self(token: null, errorMessage: $errorMessage);
    }

    public function isSuccess(): bool
    {
        return $this->token !== null;
    }
}
