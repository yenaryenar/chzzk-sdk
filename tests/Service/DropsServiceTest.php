<?php

namespace Tests\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Service\DropsService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DropsServiceTest extends TestCase
{
    private DropsService $dropsService;
    private ApiClient $apiClient;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClient::class);
        $this->dropsService = new DropsService($this->apiClient);
    }

    public function testGetRewardClaims(): void
    {
        $params = [
            'page' => 1,
            'size' => 10,
            'channelId' => 'channel123'
        ];
        $expectedResponse = [
            'content' => [
                [
                    'claimId' => 'claim123',
                    'campaignId' => 'campaign123',
                    'channelId' => 'channel123'
                ]
            ]
        ];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with(
                'GET',
                '/open/v1/drops/reward-claims',
                ['query' => $params]
            )
            ->willReturn($expectedResponse);

        $result = $this->dropsService->getRewardClaims($params);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testGetRewardClaimsWithInvalidParams(): void
    {
        $params = [
            'page' => 1,
            'invalidParam' => 'value'
        ];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with(
                'GET',
                '/open/v1/drops/reward-claims',
                ['query' => ['page' => 1]]
            )
            ->willReturn([]);

        $this->dropsService->getRewardClaims($params);
    }

    public function testUpdateRewardClaims(): void
    {
        $claimIds = ['claim1', 'claim2', 'claim3'];
        $fulfillmentState = 'FULFILLED';
        $expectedResponse = ['success' => true];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with(
                'PUT',
                '/open/v1/drops/reward-claims',
                [
                    'json' => [
                        'claimIds' => $claimIds,
                        'fulfillmentState' => $fulfillmentState
                    ]
                ]
            )
            ->willReturn($expectedResponse);

        $result = $this->dropsService->updateRewardClaims($claimIds, $fulfillmentState);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testUpdateRewardClaimsWithEmptyClaimIds(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Claim IDs cannot be empty');

        $this->dropsService->updateRewardClaims([], 'FULFILLED');
    }

    public function testUpdateRewardClaimsWithInvalidFulfillmentState(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Fulfillment state must be either CLAIMED or FULFILLED');

        $this->dropsService->updateRewardClaims(['claim1'], 'INVALID_STATE');
    }
}