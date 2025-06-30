<?php

namespace Tests\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Auth\AccessToken;
use Cherryred5959\ChzzkApi\Service\ChatService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ChatServiceTest extends TestCase
{
    private ChatService $chatService;
    private ApiClient $apiClient;
    private AccessToken $accessToken;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClient::class);
        $this->accessToken = $this->createMock(AccessToken::class);
        $this->chatService = new ChatService($this->apiClient);
    }

    public function testSendMessage(): void
    {
        $message = 'Hello World';
        $expectedResponse = ['success' => true];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with(
                'POST',
                '/open/v1/chats/send',
                ['json' => ['message' => $message]],
                $this->accessToken
            )
            ->willReturn($expectedResponse);

        $result = $this->chatService->sendMessage($this->accessToken, $message);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testSendMessageTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Message must be 100 characters or less');

        $longMessage = str_repeat('a', 101);
        $this->chatService->sendMessage($this->accessToken, $longMessage);
    }

    public function testRegisterNoticeWithMessage(): void
    {
        $message = 'Notice message';
        $expectedResponse = ['success' => true];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with(
                'POST',
                '/open/v1/chats/notice',
                ['json' => ['message' => $message]],
                $this->accessToken
            )
            ->willReturn($expectedResponse);

        $result = $this->chatService->registerNotice($this->accessToken, $message);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testRegisterNoticeWithMessageId(): void
    {
        $messageId = '123456';
        $expectedResponse = ['success' => true];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with(
                'POST',
                '/open/v1/chats/notice',
                ['json' => ['messageId' => $messageId]],
                $this->accessToken
            )
            ->willReturn($expectedResponse);

        $result = $this->chatService->registerNotice($this->accessToken, null, $messageId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testRegisterNoticeWithBothParameters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Provide either message or messageId, not both');

        $this->chatService->registerNotice($this->accessToken, 'message', '123456');
    }

    public function testGetChatSettings(): void
    {
        $expectedResponse = [
            'chatAvailableCondition' => 'ALL',
            'chatAvailableGroup' => 'ALL'
        ];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with('GET', '/open/v1/chats/settings', [], $this->accessToken)
            ->willReturn($expectedResponse);

        $result = $this->chatService->getChatSettings($this->accessToken);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testUpdateChatSettings(): void
    {
        $settings = [
            'chatAvailableCondition' => 'FOLLOWER',
            'minFollowerMinute' => 60
        ];
        $expectedResponse = ['success' => true];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with(
                'PUT',
                '/open/v1/chats/settings',
                ['json' => $settings],
                $this->accessToken
            )
            ->willReturn($expectedResponse);

        $result = $this->chatService->updateChatSettings($this->accessToken, $settings);

        $this->assertEquals($expectedResponse, $result);
    }
}