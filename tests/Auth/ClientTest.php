<?php

namespace Cherryred5959\ChzzkApi\Tests\Auth;

use Cherryred5959\ChzzkApi\Auth\AccessCode;
use Cherryred5959\ChzzkApi\Auth\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testConstruct(): void
    {
        $clientId = 'test_client_id';
        $clientSecret = 'test_client_secret';
        $redirectUri = 'https://example.com/callback';
        $accessCode = new AccessCode('code', 'state');
        
        $client = new Client($clientId, $clientSecret, $redirectUri, $accessCode);
        
        $this->assertSame($clientId, $client->getClientId());
        $this->assertSame($clientSecret, $client->getClientSecret());
        $this->assertSame($redirectUri, $client->getRedirectUri());
        $this->assertSame($accessCode, $client->getAccessCode());
    }
    
    public function testGetClientId(): void
    {
        $clientId = 'my_client_id';
        $client = new Client($clientId, 'secret', 'uri', new AccessCode('code', 'state'));
        
        $this->assertSame($clientId, $client->getClientId());
    }
    
    public function testGetClientSecret(): void
    {
        $clientSecret = 'my_client_secret';
        $client = new Client('id', $clientSecret, 'uri', new AccessCode('code', 'state'));
        
        $this->assertSame($clientSecret, $client->getClientSecret());
    }
    
    public function testGetRedirectUri(): void
    {
        $redirectUri = 'https://my-app.com/auth/callback';
        $client = new Client('id', 'secret', $redirectUri, new AccessCode('code', 'state'));
        
        $this->assertSame($redirectUri, $client->getRedirectUri());
    }
    
    public function testGetAccessCode(): void
    {
        $accessCode = new AccessCode('authorization_code', 'csrf_state');
        $client = new Client('id', 'secret', 'uri', $accessCode);
        
        $this->assertSame($accessCode, $client->getAccessCode());
    }
}