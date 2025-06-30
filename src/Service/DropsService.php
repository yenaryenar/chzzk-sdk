<?php

namespace Cherryred5959\ChzzkApi\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

readonly class DropsService
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function getRewardClaims(array $params = []): array
    {
        $allowedParams = [
            'page',
            'from',
            'size',
            'claimId',
            'channelId',
            'campaignId',
            'categoryId',
            'fulfillmentState'
        ];

        $filteredParams = array_intersect_key($params, array_flip($allowedParams));

        return $this->apiClient->makeRequest('GET', '/open/v1/drops/reward-claims', [
            'query' => $filteredParams
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function updateRewardClaims(array $claimIds, string $fulfillmentState): array
    {
        if (empty($claimIds)) {
            throw new InvalidArgumentException('Claim IDs cannot be empty');
        }

        if (!in_array($fulfillmentState, ['CLAIMED', 'FULFILLED'])) {
            throw new InvalidArgumentException('Fulfillment state must be either CLAIMED or FULFILLED');
        }

        return $this->apiClient->makeRequest('PUT', '/open/v1/drops/reward-claims', [
            'json' => [
                'claimIds' => $claimIds,
                'fulfillmentState' => $fulfillmentState
            ]
        ]);
    }
}