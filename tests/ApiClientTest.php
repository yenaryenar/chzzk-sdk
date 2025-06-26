<?php

namespace Cherryred5959\ChzzkApi\Tests;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Auth\AccessCode;
use Cherryred5959\ChzzkApi\Auth\AccessToken;
use Cherryred5959\ChzzkApi\Auth\Client;
use Cherryred5959\ChzzkApi\Auth\TokenTypeHint;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class ApiClientTest extends TestCase
{
    private HttpClient $httpClient;

    private ApiClient $apiClient;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClient::class);
        $client = new Client(
            'test_client_id',
            'test_client_secret',
            'https://example.com/callback',
            new AccessCode('test_code', 'test_state')
        );
        $this->apiClient = new ApiClient($this->httpClient, $client);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetOrCreateAccessToken(): void
    {
        $tokenResponse = [
            'accessToken' => 'test_access_token',
            'refreshToken' => 'test_refresh_token',
            'tokenType' => 'Bearer',
            'expiresIn' => 3600
        ];

        $response = $this->createMock(Response::class);
        $response->method('getBody')
            ->willReturn($this->createMockStreamWithContent(json_encode($tokenResponse)));

        $this->httpClient->expects($this->once())
            ->method('post')
            ->with('https://openapi.chzzk.naver.com/auth/v1/token', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'grantType' => 'authorization_code ',
                    'clientId' => 'test_client_id',
                    'clientSecret' => 'test_client_secret',
                    'code' => 'test_code',
                    'state' => 'test_state'
                ]
            ])
            ->willReturn($response);

        $accessToken = $this->apiClient->getOrCreateAccessToken();

        $this->assertSame('test_access_token', $accessToken->getAccessToken());
        $this->assertSame('test_refresh_token', $accessToken->getRefreshToken());
        $this->assertSame('Bearer', $accessToken->getTokenType());
        $this->assertSame(3600, $accessToken->getExpiresIn());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRefreshAccessToken(): void
    {
        $originalToken = new AccessToken('old_access', 'old_refresh', 'Bearer', 3600);
        $tokenResponse = [
            'accessToken' => 'new_access_token',
            'refreshToken' => 'new_refresh_token',
            'tokenType' => 'Bearer',
            'expiresIn' => 7200
        ];

        $response = $this->createMock(Response::class);
        $response->method('getBody')
            ->willReturn($this->createMockStreamWithContent(json_encode($tokenResponse)));

        $this->httpClient->expects($this->once())
            ->method('post')
            ->with('https://openapi.chzzk.naver.com/auth/v1/token', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'grantType' => 'refresh_token',
                    'clientId' => 'test_client_id',
                    'clientSecret' => 'test_client_secret',
                    'refreshToken' => 'old_refresh'
                ]
            ])
            ->willReturn($response);

        $newToken = $this->apiClient->refreshAccessToken($originalToken);

        $this->assertSame('new_access_token', $newToken->getAccessToken());
        $this->assertSame('new_refresh_token', $newToken->getRefreshToken());
    }

    /**
     * @throws GuzzleException
     */
    public function testRevokeAccessToken(): void
    {
        $accessToken = new AccessToken('access_token', 'refresh_token', 'Bearer', 3600);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->with('https://openapi.chzzk.naver.com/auth/v1/token/revoke', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'clientId' => 'test_client_id',
                    'clientSecret' => 'test_client_secret',
                    'token' => 'access_token',
                    'tokenTypeHint' => 'access_token'
                ]
            ]);

        $this->apiClient->revokeAccessToken($accessToken);
    }

    /**
     * @throws GuzzleException
     */
    public function testRevokeAccessTokenWithRefreshType(): void
    {
        $accessToken = new AccessToken('access_token', 'refresh_token', 'Bearer', 3600);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->with('https://openapi.chzzk.naver.com/auth/v1/token/revoke', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'clientId' => 'test_client_id',
                    'clientSecret' => 'test_client_secret',
                    'token' => 'refresh_token',
                    'tokenTypeHint' => 'refresh_token'
                ]
            ]);

        $this->apiClient->revokeAccessToken($accessToken, TokenTypeHint::Refresh);
    }

    /**
     * @throws Exception
     */
    public function testMakeRequestWithoutAccessToken(): void
    {
        $responseData = ['data' => 'test'];
        $response = $this->createMock(Response::class);
        $response->method('getBody')
            ->willReturn($this->createMockStreamWithContent(json_encode($responseData)));

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://openapi.chzzk.naver.com/test/endpoint', [
                'headers' => ['Content-Type' => 'application/json']
            ])
            ->willReturn($response);

        $result = $this->apiClient->makeRequest('GET', '/test/endpoint');

        $this->assertSame($responseData, $result);
    }

    /**
     * @throws Exception
     */
    public function testMakeRequestWithAccessToken(): void
    {
        $accessToken = new AccessToken('test_token', 'refresh', 'Bearer', 3600);
        $responseData = ['data' => 'test'];
        $response = $this->createMock(Response::class);
        $response->method('getBody')
            ->willReturn($this->createMockStreamWithContent(json_encode($responseData)));

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('POST', 'https://openapi.chzzk.naver.com/test/endpoint', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer test_token'
                ],
                'json' => ['key' => 'value']
            ])
            ->willReturn($response);

        $result = $this->apiClient->makeRequest('POST', '/test/endpoint', [
            'json' => ['key' => 'value']
        ], $accessToken);

        $this->assertSame($responseData, $result);
    }

    /**
     * @throws Exception
     */
    private function createMockStreamWithContent(string $content): StreamInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($content);
        return $stream;
    }
}
