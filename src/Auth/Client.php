<?php

namespace Cherryred5959\ChzzkApi\Auth;

readonly class Client
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private string $redirectUri,
        private AccessCode $accessCode
    ) {
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function getAccessCode(): AccessCode
    {
        return $this->accessCode;
    }
}
