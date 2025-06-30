<?php

namespace Cherryred5959\ChzzkApi;

use Cherryred5959\ChzzkApi\Auth\Client;
use Cherryred5959\ChzzkApi\Service\CategoryService;
use Cherryred5959\ChzzkApi\Service\ChannelService;
use Cherryred5959\ChzzkApi\Service\ChatService;
use Cherryred5959\ChzzkApi\Service\DropsService;
use Cherryred5959\ChzzkApi\Service\LiveService;
use Cherryred5959\ChzzkApi\Service\UserService;
use GuzzleHttp\Client as HttpClient;

class ChzzkSdk
{
    private ApiClient $apiClient;
    private UserService $userService;
    private ChannelService $channelService;
    private LiveService $liveService;
    private CategoryService $categoryService;
    private ChatService $chatService;
    private DropsService $dropsService;

    public function __construct(Client $client, HttpClient $httpClient = null)
    {
        $this->apiClient = new ApiClient($httpClient ?? new HttpClient(), $client);
        $this->userService = new UserService($this->apiClient);
        $this->channelService = new ChannelService($this->apiClient);
        $this->liveService = new LiveService($this->apiClient);
        $this->categoryService = new CategoryService($this->apiClient);
        $this->chatService = new ChatService($this->apiClient);
        $this->dropsService = new DropsService($this->apiClient);
    }

    public function getApiClient(): ApiClient
    {
        return $this->apiClient;
    }

    public function users(): UserService
    {
        return $this->userService;
    }

    public function channels(): ChannelService
    {
        return $this->channelService;
    }

    public function lives(): LiveService
    {
        return $this->liveService;
    }

    public function categories(): CategoryService
    {
        return $this->categoryService;
    }

    public function chats(): ChatService
    {
        return $this->chatService;
    }

    public function drops(): DropsService
    {
        return $this->dropsService;
    }
}