<?php

namespace Cherryred5959\ChzzkApi\Tests;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Auth\AccessCode;
use Cherryred5959\ChzzkApi\Auth\Client;
use Cherryred5959\ChzzkApi\ChzzkSdk;
use Cherryred5959\ChzzkApi\Service\ChannelService;
use Cherryred5959\ChzzkApi\Service\LiveService;
use Cherryred5959\ChzzkApi\Service\UserService;
use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ChzzkSdkTest extends TestCase
{
    private Client $client;
    private HttpClient $httpClient;
    private ChzzkSdk $sdk;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = new Client(
            'test_client_id',
            'test_client_secret',
            'https://example.com/callback',
            new AccessCode('test_code', 'test_state')
        );
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->sdk = new ChzzkSdk($this->client, $this->httpClient);
    }

    public function testConstructorWithCustomHttpClient(): void
    {
        $sdk = new ChzzkSdk($this->client, $this->httpClient);
        
        $this->assertInstanceOf(ApiClient::class, $sdk->getApiClient());
    }

    public function testConstructorWithDefaultHttpClient(): void
    {
        $sdk = new ChzzkSdk($this->client);
        
        $this->assertInstanceOf(ApiClient::class, $sdk->getApiClient());
    }

    public function testGetApiClient(): void
    {
        $this->assertInstanceOf(ApiClient::class, $this->sdk->getApiClient());
    }

    public function testUsers(): void
    {
        $this->assertInstanceOf(UserService::class, $this->sdk->users());
    }

    public function testChannels(): void
    {
        $this->assertInstanceOf(ChannelService::class, $this->sdk->channels());
    }

    public function testLives(): void
    {
        $this->assertInstanceOf(LiveService::class, $this->sdk->lives());
    }

    public function testServicesSingleton(): void
    {
        $userService1 = $this->sdk->users();
        $userService2 = $this->sdk->users();
        
        $this->assertSame($userService1, $userService2);

        $channelService1 = $this->sdk->channels();
        $channelService2 = $this->sdk->channels();
        
        $this->assertSame($channelService1, $channelService2);

        $liveService1 = $this->sdk->lives();
        $liveService2 = $this->sdk->lives();
        
        $this->assertSame($liveService1, $liveService2);
    }

    public function testApiClientSingleton(): void
    {
        $apiClient1 = $this->sdk->getApiClient();
        $apiClient2 = $this->sdk->getApiClient();
        
        $this->assertSame($apiClient1, $apiClient2);
    }
}