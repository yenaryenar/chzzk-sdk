<?php

namespace Cherryred5959\ChzzkApi\Tests\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Auth\AccessToken;
use Cherryred5959\ChzzkApi\Service\UserService;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private ApiClient $apiClient;
    private UserService $userService;
    private AccessToken $accessToken;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClient::class);
        $this->userService = new UserService($this->apiClient);
        $this->accessToken = new AccessToken('access', 'refresh', 'Bearer', 3600);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetMe(): void
    {
        $expectedResponse = [
            'code' => 200,
            'message' => 'OK',
            'content' => [
                'userId' => 'test_user_id',
                'nickname' => 'test_nickname'
            ]
        ];

        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/users/me', [], $this->accessToken)
            ->willReturn($expectedResponse);

        $result = $this->userService->getMe($this->accessToken);

        $this->assertSame($expectedResponse, $result);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetMePassesCorrectParameters(): void
    {
        $this->apiClient->expects($this->once())
            ->method('makeRequest')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('/open/v1/users/me'),
                $this->equalTo([]),
                $this->equalTo($this->accessToken)
            )
            ->willReturn([]);

        $this->userService->getMe($this->accessToken);
    }
}