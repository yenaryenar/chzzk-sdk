<?php

namespace Cherryred5959\ChzzkApi\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

readonly class ChannelService
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function getChannels(array $channelIds): array
    {
        if (count($channelIds) > 20) {
            throw new InvalidArgumentException('Maximum 20 channel IDs allowed');
        }

        $queryParams = ['channelIds' => implode(',', $channelIds)];
        
        return $this->apiClient->makeRequest('GET', '/open/v1/channels', [
            'query' => $queryParams
        ]);
    }
}