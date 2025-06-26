<?php

namespace Cherryred5959\ChzzkApi\Tests\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Auth\AccessToken;
use Cherryred5959\ChzzkApi\Service\LiveService;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class LiveServiceTest extends TestCase
{
    private ApiClient $apiClient;
    private LiveService $liveService;
    private AccessToken $accessToken;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClient::class);
        $this->liveService = new LiveService($this->apiClient);
        $this->accessToken = new AccessToken('access', 'refresh', 'Bearer', 3600);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetLiveListWithDefaults(): void
    {
        $expectedResponse = [
            'code' => 200,
            'message' => 'OK',
            'content' => ['lives' => []]
        ];

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/lives', [
                'query' => ['size' => 20]
            ])
            ->willReturn($expectedResponse);

        $result = $this->liveService->getLiveList();

        $this->assertSame($expectedResponse, $result);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetLiveListWithCustomSize(): void
    {
        $size = 10;

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/lives', [
                'query' => ['size' => $size]
            ])
            ->willReturn([]);

        $this->liveService->getLiveList($size);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetLiveListWithNext(): void
    {
        $size = 15;
        $next = 'next_cursor';

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/lives', [
                'query' => ['size' => $size, 'next' => $next]
            ])
            ->willReturn([]);

        $this->liveService->getLiveList($size, $next);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetLiveListThrowsExceptionForInvalidSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Size must be between 1 and 20');

        $this->liveService->getLiveList(0);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetLiveListThrowsExceptionForTooLargeSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Size must be between 1 and 20');

        $this->liveService->getLiveList(21);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetStreamKey(): void
    {
        $expectedResponse = [
            'code' => 200,
            'message' => 'OK',
            'content' => ['streamKey' => 'test_stream_key']
        ];

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/streams/key', [], $this->accessToken)
            ->willReturn($expectedResponse);

        $result = $this->liveService->getStreamKey($this->accessToken);

        $this->assertSame($expectedResponse, $result);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetLiveSetting(): void
    {
        $expectedResponse = [
            'code' => 200,
            'message' => 'OK',
            'content' => ['title' => 'Live Title']
        ];

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/lives/setting', [], $this->accessToken)
            ->willReturn($expectedResponse);

        $result = $this->liveService->getLiveSetting($this->accessToken);

        $this->assertSame($expectedResponse, $result);
    }

    /**
     * @throws GuzzleException
     */
    public function testUpdateLiveSetting(): void
    {
        $settings = ['title' => 'New Live Title', 'description' => 'New Description'];
        $expectedResponse = [
            'code' => 200,
            'message' => 'OK'
        ];

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('PATCH', '/open/v1/lives/setting', [
                'json' => $settings
            ], $this->accessToken)
            ->willReturn($expectedResponse);

        $result = $this->liveService->updateLiveSetting($this->accessToken, $settings);

        $this->assertSame($expectedResponse, $result);
    }
}