<?php

namespace Cherryred5959\ChzzkApi;

use Cherryred5959\ChzzkApi\Auth\AccessToken;
use Cherryred5959\ChzzkApi\Auth\Client;
use Cherryred5959\ChzzkApi\Auth\TokenTypeHint;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private const string BASE_URL = "https://openapi.chzzk.naver.com";

    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly Client $client,
    ) {
    }

    /**
     * @throws GuzzleException
     */
    private function getAccessToken(): AccessToken
    {
        $response = $this->httpClient->post(self::BASE_URL . "/auth/v1/token", [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            "json" => [
                "grantType" => "authorization_code",
                "clientId" => $this->client->getClientId(),
                "clientSecret" => $this->client->getClientSecret(),
                "code" => $this->client->getAccessCode()->getCode(),
                "state" => $this->client->getAccessCode()->getState(),
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        return new AccessToken(
            accessToken: $responseBody['accessToken'],
            refreshToken: $responseBody['refreshToken'],
            tokenType: $responseBody['tokenType'],
            expiresIn: $responseBody['expiresIn']
        );
    }

    /**
     * @throws GuzzleException
     */
    public function refreshAccessToken(AccessToken $accessToken): AccessToken
    {
        $response = $this->httpClient->post(self::BASE_URL . "/auth/v1/token", [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            "json" => [
                "grantType" => "refresh_token",
                "clientId" => $this->client->getClientId(),
                "clientSecret" => $this->client->getClientSecret(),
                "refreshToken" => $accessToken->getRefreshToken(),
            ]
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        return new AccessToken(
            accessToken: $responseBody['accessToken'],
            refreshToken: $responseBody['refreshToken'],
            tokenType: $responseBody['tokenType'],
            expiresIn: $responseBody['expiresIn']
        );
    }

    /**
     * @throws GuzzleException
     */
    public function revokeAccessToken(
        AccessToken $accessToken,
        TokenTypeHint $tokenTypeHint = TokenTypeHint::Access
    ): void {
        $this->httpClient->post(self::BASE_URL . "/auth/v1/token/revoke", [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            "json" => [
                "clientId" => $this->client->getClientId(),
                "clientSecret" => $this->client->getClientSecret(),
                "token" => $tokenTypeHint->getToken($accessToken),
                "tokenTypeHint" => $tokenTypeHint->value
            ]
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function makeRequest(string $method, string $endpoint, array $options = [], AccessToken $accessToken = null): array
    {
        $url = self::BASE_URL . $endpoint;

        $defaultOptions = [
            "headers" => [
                "Content-Type" => "application/json"
            ]
        ];

        if ($accessToken) {
            $defaultOptions["headers"]["Authorization"] = "Bearer " . $accessToken->getAccessToken();
        }

        $options = array_merge_recursive($defaultOptions, $options);

        $response = $this->httpClient->request($method, $url, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getOrCreateAccessToken(): AccessToken
    {
        return $this->getAccessToken();
    }
}
