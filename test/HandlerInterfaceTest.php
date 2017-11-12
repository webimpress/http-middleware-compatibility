<?php

namespace WebimpressTest\HttpMiddlewareCompatibility;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionMethod;
use Webimpress\HttpMiddlewareCompatibility\HandlerInterface;
use const Webimpress\HttpMiddlewareCompatibility\HANDLER_METHOD;
use const Webimpress\HttpMiddlewareCompatibility\HAS_RETURN_TYPE;

class HandlerInterfaceTest extends TestCase
{
    public function testHandlerInterfaceIsDefined()
    {
        self::assertTrue(interface_exists(HandlerInterface::class));
    }

    public function testHandlerInterfaceIsAliasToBaseLibrary()
    {
        // http-middleware 0.3.0, 0.2.0, 0.1.1
        if (interface_exists(\Interop\Http\Middleware\DelegateInterface::class)) {
            self::assertTrue(is_a(HandlerInterface::class, \Interop\Http\Middleware\DelegateInterface::class, true));
            self::assertTrue(is_a(\Interop\Http\Middleware\DelegateInterface::class, HandlerInterface::class, true));
            self::assertSame(
                method_exists(HandlerInterface::class, 'next') ? 'next' : 'process',
                HANDLER_METHOD
            );
            self::assertFalse(HAS_RETURN_TYPE);

            return;
        }

        // http-middleware 0.4.1
        if (interface_exists(\Interop\Http\ServerMiddleware\DelegateInterface::class)) {
            self::assertTrue(is_a(HandlerInterface::class, \Interop\Http\ServerMiddleware\DelegateInterface::class, true));
            self::assertTrue(is_a(\Interop\Http\ServerMiddleware\DelegateInterface::class, HandlerInterface::class, true));
            self::assertSame('process', HANDLER_METHOD);
            self::assertFalse(HAS_RETURN_TYPE);

            return;
        }

        // http-middleware 0.5.0 & http-server-middleware 1.0.1
        if (interface_exists(\Interop\Http\Server\RequestHandlerInterface::class)) {
            self::assertTrue(is_a(HandlerInterface::class, \Interop\Http\Server\RequestHandlerInterface::class, true));
            self::assertTrue(is_a(\Interop\Http\Server\RequestHandlerInterface::class, HandlerInterface::class, true));
            self::assertSame('handle', HANDLER_METHOD);
            $hasReturnType = false;
            if (PHP_VERSION_ID >= 70000) {
                $r = new ReflectionMethod(HandlerInterface::class, 'handle');
                $hasReturnType = $r->hasReturnType();
            }
            self::assertSame($hasReturnType, HAS_RETURN_TYPE);

            return;
        }

        self::fail('Unsupported version');
    }

    public function testHandlerMethodExists()
    {
        $request = $this->prophesize(ServerRequestInterface::class);
        $prophecy = $this->prophesize(HandlerInterface::class);
        $prophecy->{HANDLER_METHOD}(Argument::any())->shouldBeCalledTimes(1);

        $prophecy->reveal()->{HANDLER_METHOD}($request->reveal());
    }
}
