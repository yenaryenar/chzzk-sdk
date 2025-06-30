<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cherryred5959\ChzzkApi\Auth\AccessCode;
use Cherryred5959\ChzzkApi\Auth\Client;
use Cherryred5959\ChzzkApi\ChzzkSdk;

// 클라이언트 설정
$client = new Client(
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret',
    redirectUri: 'https://example.com/callback', // OAuth 콜백 URL
    accessCode: new AccessCode(
        code: 'authorization_code_from_oauth_flow',
        state: 'state_value'
    )
);

// SDK 초기화
$sdk = new ChzzkSdk($client);

try {
    // 액세스 토큰 획득
    $accessToken = $sdk->getApiClient()->getOrCreateAccessToken();

    echo "액세스 토큰 획득 성공!\n";
    echo "토큰 타입: " . $accessToken->getTokenType() . "\n";
    echo "만료 시간: " . $accessToken->getExpiresIn() . "초\n\n";

    // 사용자 정보 조회
    $userInfo = $sdk->users()->getMe($accessToken);
    echo "사용자 정보:\n";
    echo "채널 ID: " . $userInfo['channelId'] . "\n";
    echo "채널 이름: " . $userInfo['channelName'] . "\n\n";

    // 채널 정보 조회
    $channels = $sdk->channels()->getChannels([$userInfo['channelId']]);
    echo "채널 정보:\n";
    if (!empty($channels['data'])) {
        $channel = $channels['data'][0];
        echo "채널 이름: " . $channel['channelName'] . "\n";
        echo "팔로워 수: " . $channel['followerCount'] . "\n";
        echo "이미지 URL: " . $channel['channelImageUrl'] . "\n\n";
    }

    // 라이브 목록 조회
    $liveList = $sdk->lives()->getLiveList(10);
    echo "라이브 목록 (최대 10개):\n";
    if (!empty($liveList['data'])) {
        foreach ($liveList['data'] as $live) {
            echo "- " . $live['title'] . " (시청자: " . $live['currentViewerCount'] . "명)\n";
        }
    }

} catch (Exception $e) {
    echo "오류 발생: " . $e->getMessage() . "\n";
}
