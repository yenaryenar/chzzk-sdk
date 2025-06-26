<?php

namespace Cherryred5959\ChzzkApi\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Auth\AccessToken;
use GuzzleHttp\Exception\GuzzleException;

readonly class UserService
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function getMe(AccessToken $accessToken): array
    {
        return $this->apiClient->makeRequest('GET', '/open/v1/users/me', [], $accessToken);
    }
}