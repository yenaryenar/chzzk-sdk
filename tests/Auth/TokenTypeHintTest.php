<?php

namespace Cherryred5959\ChzzkApi\Tests\Auth;

use Cherryred5959\ChzzkApi\Auth\AccessToken;
use Cherryred5959\ChzzkApi\Auth\TokenTypeHint;
use PHPUnit\Framework\TestCase;

class TokenTypeHintTest extends TestCase
{
    public function testAccessTokenType(): void
    {
        $this->assertSame('access_token', TokenTypeHint::Access->value);
    }
    
    public function testRefreshTokenType(): void
    {
        $this->assertSame('refresh_token', TokenTypeHint::Refresh->value);
    }
    
    public function testGetTokenWithAccessType(): void
    {
        $accessToken = new AccessToken('access_123', 'refresh_456', 'Bearer', 3600);
        $hint = TokenTypeHint::Access;
        
        $this->assertSame('access_123', $hint->getToken($accessToken));
    }
    
    public function testGetTokenWithRefreshType(): void
    {
        $accessToken = new AccessToken('access_123', 'refresh_456', 'Bearer', 3600);
        $hint = TokenTypeHint::Refresh;
        
        $this->assertSame('refresh_456', $hint->getToken($accessToken));
    }
    
    public function testAllTokenTypes(): void
    {
        $accessToken = new AccessToken('access_token', 'refresh_token', 'Bearer', 3600);
        
        $this->assertSame('access_token', TokenTypeHint::Access->getToken($accessToken));
        $this->assertSame('refresh_token', TokenTypeHint::Refresh->getToken($accessToken));
    }
}