<?php

namespace Cherryred5959\ChzzkApi\Tests\Auth;

use Cherryred5959\ChzzkApi\Auth\AccessCode;
use PHPUnit\Framework\TestCase;

class AccessCodeTest extends TestCase
{
    public function testConstruct(): void
    {
        $code = 'test_code';
        $state = 'test_state';
        
        $accessCode = new AccessCode($code, $state);
        
        $this->assertSame($code, $accessCode->getCode());
        $this->assertSame($state, $accessCode->getState());
    }
    
    public function testGetCode(): void
    {
        $code = 'authorization_code_123';
        $accessCode = new AccessCode($code, 'state');
        
        $this->assertSame($code, $accessCode->getCode());
    }
    
    public function testGetState(): void
    {
        $state = 'csrf_protection_state';
        $accessCode = new AccessCode('code', $state);
        
        $this->assertSame($state, $accessCode->getState());
    }
}