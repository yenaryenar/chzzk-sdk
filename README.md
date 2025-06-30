# chzzk-sdk
[치지직(Chzzk) Open API](https://chzzk.gitbook.io/chzzk) 비공식 PHP SDK

Powered by Claude Code and JetBrains AI

## 설치

```bash
composer install cherryred5959/chzzk-sdk
```

## 사용법

### 1. 기본 설정

```php
use Cherryred5959\ChzzkApi\Auth\AccessCode;
use Cherryred5959\ChzzkApi\Auth\Client;
use Cherryred5959\ChzzkApi\ChzzkSdk;

$client = new Client(
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret',
    redirectUri: 'https://example.com/callback', // OAuth 콜백 URL
    accessCode: new AccessCode(
        code: 'authorization_code_from_oauth_flow',
        state: 'state_value'
    )
);

$sdk = new ChzzkSdk($client);
```

### 2. 인증

```php
// 액세스 토큰 획득
$accessToken = $sdk->getApiClient()->getOrCreateAccessToken();

// 토큰 갱신
$newAccessToken = $sdk->getApiClient()->refreshAccessToken($accessToken);

// 토큰 폐기
$sdk->getApiClient()->revokeAccessToken($accessToken);
```

### 3. 사용자 API

```php
// 내 정보 조회
$userInfo = $sdk->users()->getMe($accessToken);
echo $userInfo['channelName']; // 채널 이름
```

### 4. 채널 API

```php
// 채널 정보 조회 (최대 20개)
$channels = $sdk->channels()->getChannels(['channel_id_1', 'channel_id_2']);
```

### 5. 라이브 API

```php
// 라이브 목록 조회
$liveList = $sdk->lives()->getLiveList(size: 10, next: null);

// 스트림 키 조회 (인증 필요)
$streamKey = $sdk->lives()->getStreamKey($accessToken);

// 라이브 설정 조회 (인증 필요)
$liveSetting = $sdk->lives()->getLiveSetting($accessToken);

// 라이브 설정 업데이트 (인증 필요)
$updatedSetting = $sdk->lives()->updateLiveSetting($accessToken, [
    'title' => '새로운 라이브 제목',
    'categoryType' => 'GAME',
    'categoryId' => 'game_category_id',
    'tags' => ['태그1', '태그2']
]);
```

### 6. 카테고리 API

```php
// 카테고리 검색
$categories = $sdk->categories()->searchCategories(
    query: '게임',    // 검색할 카테고리 이름
    size: 10         // 결과 개수 (1-50, 기본값: 20)
);

foreach ($categories['content'] as $category) {
    echo "카테고리: " . $category['categoryValue'] . "\n";
    echo "타입: " . $category['categoryType'] . "\n";
    echo "이미지: " . $category['posterImageUrl'] . "\n";
}
```

### 7. 채팅 API

```php
// 채팅 메시지 전송 (인증 필요)
$result = $sdk->chats()->sendMessage($accessToken, '안녕하세요!');

// 채팅 공지 등록 - 새 메시지로 (인증 필요)
$notice = $sdk->chats()->registerNotice($accessToken, message: '공지사항입니다');

// 채팅 공지 등록 - 기존 메시지 ID로 (인증 필요)
$notice = $sdk->chats()->registerNotice($accessToken, messageId: 'message_id_123');

// 채팅 설정 조회 (인증 필요)
$settings = $sdk->chats()->getChatSettings($accessToken);

// 채팅 설정 업데이트 (인증 필요)
$updatedSettings = $sdk->chats()->updateChatSettings($accessToken, [
    'chatAvailableCondition' => 'FOLLOWER',     // 채팅 참여 조건
    'chatAvailableGroup' => 'ALL',              // 채팅 참여 그룹
    'minFollowerMinute' => 60,                  // 최소 팔로우 시간 (분)
    'allowSubscriberInFollowerMode' => true     // 팔로워 모드에서 구독자 허용
]);
```

### 8. 드롭스 API

```php
// 드롭스 보상 클레임 조회
$claims = $sdk->drops()->getRewardClaims([
    'page' => 1,
    'size' => 20,
    'channelId' => 'channel_id_123',
    'campaignId' => 'campaign_id_456',
    'fulfillmentState' => 'CLAIMED'
]);

// 드롭스 보상 클레임 상태 업데이트
$result = $sdk->drops()->updateRewardClaims(
    claimIds: ['claim_1', 'claim_2', 'claim_3'],
    fulfillmentState: 'FULFILLED'  // 'CLAIMED' 또는 'FULFILLED'
);
```

## 지원하는 API

- ✅ 인증 (Authorization)
- ✅ 사용자 (User)  
- ✅ 채널 (Channel)
- ✅ 라이브 (Live)
- ✅ 채팅 (Chat)
- ✅ 드롭스 (Drops)
- ✅ 카테고리 (Category)

## 라이센스

MIT License
