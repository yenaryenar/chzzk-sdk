<?php

namespace Cherryred5959\ChzzkApi\Tests\Auth;

use Cherryred5959\ChzzkApi\Auth\AccessToken;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    public function testConstruct(): void
    {
        $accessToken = 'access_token_123';
        $refreshToken = 'refresh_token_456';
        $tokenType = 'Bearer';
        $expiresIn = 3600;
        
        $token = new AccessToken($accessToken, $refreshToken, $tokenType, $expiresIn);
        
        $this->assertSame($accessToken, $token->getAccessToken());
        $this->assertSame($refreshToken, $token->getRefreshToken());
        $this->assertSame($tokenType, $token->getTokenType());
        $this->assertSame($expiresIn, $token->getExpiresIn());
    }
    
    public function testGetAccessToken(): void
    {
        $accessToken = 'test_access_token';
        $token = new AccessToken($accessToken, 'refresh', 'Bearer', 3600);
        
        $this->assertSame($accessToken, $token->getAccessToken());
    }
    
    public function testGetRefreshToken(): void
    {
        $refreshToken = 'test_refresh_token';
        $token = new AccessToken('access', $refreshToken, 'Bearer', 3600);
        
        $this->assertSame($refreshToken, $token->getRefreshToken());
    }
    
    public function testGetTokenType(): void
    {
        $tokenType = 'Bearer';
        $token = new AccessToken('access', 'refresh', $tokenType, 3600);
        
        $this->assertSame($tokenType, $token->getTokenType());
    }
    
    public function testGetExpiresIn(): void
    {
        $expiresIn = 7200;
        $token = new AccessToken('access', 'refresh', 'Bearer', $expiresIn);
        
        $this->assertSame($expiresIn, $token->getExpiresIn());
    }
}