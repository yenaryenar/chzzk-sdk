<?php

namespace Cherryred5959\ChzzkApi\Auth;

readonly class AccessToken
{
    private string $accessToken;
    private string $refreshToken;
    private string $tokenType; // Only "Bearer"
    private int $expiresIn; // sec

    public function __construct(
        string $accessToken,
        string $refreshToken,
        string $tokenType,
        int $expiresIn
    ) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}
