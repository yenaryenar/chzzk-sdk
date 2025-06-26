<?php

namespace Cherryred5959\ChzzkApi\Auth;

readonly class AccessCode
{
    public function __construct(
        private string $code,
        private string $state
    ) {}

    public function getCode(): string
    {
        return $this->code;
    }

    public function getState(): string
    {
        return $this->state;
    }
}
