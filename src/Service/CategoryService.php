<?php

namespace Cherryred5959\ChzzkApi\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

readonly class CategoryService
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function searchCategories(string $query, int $size = 20): array
    {
        if ($size < 1 || $size > 50) {
            throw new InvalidArgumentException('Size must be between 1 and 50');
        }

        if (empty($query)) {
            throw new InvalidArgumentException('Query parameter is required');
        }

        $queryParams = [
            'query' => $query,
            'size' => $size
        ];

        return $this->apiClient->makeRequest('GET', '/open/v1/categories/search', [
            'query' => $queryParams
        ]);
    }
}