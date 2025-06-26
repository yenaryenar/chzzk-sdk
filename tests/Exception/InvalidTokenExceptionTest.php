<?php

namespace Cherryred5959\ChzzkApi\Tests\Exception;

use Cherryred5959\ChzzkApi\Exception\InvalidTokenException;
use Exception;
use PHPUnit\Framework\TestCase;

class InvalidTokenExceptionTest extends TestCase
{
    public function testExtendsException(): void
    {
        $this->assertInstanceOf(Exception::class, new InvalidTokenException());
    }
    
    public function testCanBeInstantiated(): void
    {
        $exception = new InvalidTokenException();
        
        $this->assertInstanceOf(InvalidTokenException::class, $exception);
    }
    
    public function testCanBeInstantiatedWithMessage(): void
    {
        $message = 'Invalid token provided';
        $exception = new InvalidTokenException($message);
        
        $this->assertSame($message, $exception->getMessage());
    }
    
    public function testCanBeInstantiatedWithMessageAndCode(): void
    {
        $message = 'Token expired';
        $code = 401;
        $exception = new InvalidTokenException($message, $code);
        
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }
    
    public function testCanBeInstantiatedWithPreviousException(): void
    {
        $previous = new Exception('Previous exception');
        $exception = new InvalidTokenException('Token error', 0, $previous);
        
        $this->assertSame($previous, $exception->getPrevious());
    }
}