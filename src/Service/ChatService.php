<?php

namespace Cherryred5959\ChzzkApi\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Auth\AccessToken;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

readonly class ChatService
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function sendMessage(AccessToken $accessToken, string $message): array
    {
        if (strlen($message) > 100) {
            throw new InvalidArgumentException('Message must be 100 characters or less');
        }

        if (empty($message)) {
            throw new InvalidArgumentException('Message cannot be empty');
        }

        return $this->apiClient->makeRequest('POST', '/open/v1/chats/send', [
            'json' => ['message' => $message]
        ], $accessToken);
    }

    /**
     * @throws GuzzleException
     */
    public function registerNotice(AccessToken $accessToken, string $message = null, string $messageId = null): array
    {
        if ($message && $messageId) {
            throw new InvalidArgumentException('Provide either message or messageId, not both');
        }

        if (!$message && !$messageId) {
            throw new InvalidArgumentException('Either message or messageId is required');
        }

        $payload = [];
        if ($message) {
            if (strlen($message) > 100) {
                throw new InvalidArgumentException('Message must be 100 characters or less');
            }
            $payload['message'] = $message;
        } else {
            $payload['messageId'] = $messageId;
        }

        return $this->apiClient->makeRequest('POST', '/open/v1/chats/notice', [
            'json' => $payload
        ], $accessToken);
    }

    /**
     * @throws GuzzleException
     */
    public function getChatSettings(AccessToken $accessToken): array
    {
        return $this->apiClient->makeRequest('GET', '/open/v1/chats/settings', [], $accessToken);
    }

    /**
     * @throws GuzzleException
     */
    public function updateChatSettings(AccessToken $accessToken, array $settings): array
    {
        $allowedKeys = [
            'chatAvailableCondition',
            'chatAvailableGroup',
            'minFollowerMinute',
            'allowSubscriberInFollowerMode'
        ];

        $filteredSettings = array_intersect_key($settings, array_flip($allowedKeys));

        if (empty($filteredSettings)) {
            throw new InvalidArgumentException('At least one valid setting must be provided');
        }

        return $this->apiClient->makeRequest('PUT', '/open/v1/chats/settings', [
            'json' => $filteredSettings
        ], $accessToken);
    }
}