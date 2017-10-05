<?php

namespace WebimpressTest\HttpMiddlewareCompatibility;

use PHPUnit\Framework\TestCase;
use Webimpress\HttpMiddlewareCompatibility\MiddlewareInterface;

class MiddlewareInterfaceTest extends TestCase
{
    public function testMiddlewareInterfaceIsDefined()
    {
        self::assertTrue(interface_exists(MiddlewareInterface::class));
    }

    public function testMiddlewareInterfaceIsAliasToBaseLibrary()
    {
        // 0.3.0
        if (interface_exists(\Interop\Http\Middleware\ServerMiddlewareInterface::class)) {
            self::assertTrue(is_a(MiddlewareInterface::class, \Interop\Http\Middleware\ServerMiddlewareInterface::class, true));
            self::assertTrue(is_a(\Interop\Http\Middleware\ServerMiddlewareInterface::class, MiddlewareInterface::class, true));

            return;
        }

        // 0.4.1
        if (interface_exists(\Interop\Http\ServerMiddleware\MiddlewareInterface::class)) {
            self::assertTrue(is_a(MiddlewareInterface::class, \Interop\Http\ServerMiddleware\MiddlewareInterface::class, true));
            self::assertTrue(is_a(\Interop\Http\ServerMiddleware\MiddlewareInterface::class, MiddlewareInterface::class, true));

            return;
        }

        // 0.5.0
        if (interface_exists(\Interop\Http\Server\MiddlewareInterface::class)) {
            self::assertTrue(is_a(MiddlewareInterface::class, \Interop\Http\Server\MiddlewareInterface::class, true));
            self::assertTrue(is_a(\Interop\Http\Server\MiddlewareInterface::class, MiddlewareInterface::class, true));

            return;
        }

        self::fail('Unsupported version');
    }
}
