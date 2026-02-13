<?php

namespace App\DTOs;

readonly class AuthCredentialDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public \App\Models\User $user,
        public ?string $password = null,
        public ?string $machineCode = null,
    )
    {
        //
    }

    public static function usingPassword(\App\Models\User $user, string $password): self
    {
        return new self(user: $user, password: $password, machineCode: null);
    }

    public static function usingMachine(\App\Models\User $user, string $machineCode): self
    {
        return new self(user: $user, password: null, machineCode: $machineCode);
    }
}
