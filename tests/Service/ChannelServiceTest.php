<?php

namespace Cherryred5959\ChzzkApi\Tests\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Service\ChannelService;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ChannelServiceTest extends TestCase
{
    private ApiClient $apiClient;
    private ChannelService $channelService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClient::class);
        $this->channelService = new ChannelService($this->apiClient);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetChannels(): void
    {
        $channelIds = ['channel1', 'channel2', 'channel3'];
        $expectedResponse = [
            'code' => 200,
            'message' => 'OK',
            'content' => [
                'channels' => []
            ]
        ];

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/channels', [
                'query' => ['channelIds' => 'channel1,channel2,channel3']
            ])
            ->willReturn($expectedResponse);

        $result = $this->channelService->getChannels($channelIds);

        $this->assertSame($expectedResponse, $result);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetChannelsWithSingleId(): void
    {
        $channelIds = ['single_channel'];

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/channels', [
                'query' => ['channelIds' => 'single_channel']
            ])
            ->willReturn([]);

        $this->channelService->getChannels($channelIds);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetChannelsWithMaximumAllowedIds(): void
    {
        $channelIds = array_fill(0, 20, 'channel');

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->willReturn([]);

        $this->channelService->getChannels($channelIds);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetChannelsThrowsExceptionWhenTooManyIds(): void
    {
        $channelIds = array_fill(0, 21, 'channel');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum 20 channel IDs allowed');

        $this->channelService->getChannels($channelIds);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetChannelsWithEmptyArray(): void
    {
        $channelIds = [];

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/channels', [
                'query' => ['channelIds' => '']
            ])
            ->willReturn([]);

        $this->channelService->getChannels($channelIds);
    }
}