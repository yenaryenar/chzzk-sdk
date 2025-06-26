# chzzk-sdk
[치지직(Chzzk) Open API]((https://chzzk.gitbook.io/chzzk)) 비공식 PHP SDK

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

## 지원하는 API

- ✅ 인증 (Authorization)
- ✅ 사용자 (User)  
- ✅ 채널 (Channel)
- ✅ 라이브 (Live)
- ⏳ 채팅 (Chat) - 예정
- ⏳ 드롭스 (Drops) - 예정
- ⏳ 카테고리 (Category) - 예정

## 라이센스

MIT License
