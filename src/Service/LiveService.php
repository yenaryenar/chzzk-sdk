<?php

namespace Cherryred5959\ChzzkApi\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Auth\AccessToken;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

readonly class LiveService
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function getLiveList(int $size = 20, string $next = null): array
    {
        if ($size < 1 || $size > 20) {
            throw new InvalidArgumentException('Size must be between 1 and 20');
        }

        $queryParams = ['size' => $size];
        if ($next) {
            $queryParams['next'] = $next;
        }

        return $this->apiClient->makeRequest('GET', '/open/v1/lives', [
            'query' => $queryParams
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function getStreamKey(AccessToken $accessToken): array
    {
        return $this->apiClient->makeRequest('GET', '/open/v1/streams/key', [], $accessToken);
    }

    /**
     * @throws GuzzleException
     */
    public function getLiveSetting(AccessToken $accessToken): array
    {
        return $this->apiClient->makeRequest('GET', '/open/v1/lives/setting', [], $accessToken);
    }

    /**
     * @throws GuzzleException
     */
    public function updateLiveSetting(AccessToken $accessToken, array $settings): array
    {
        return $this->apiClient->makeRequest('PATCH', '/open/v1/lives/setting', [
            'json' => $settings
        ], $accessToken);
    }
}