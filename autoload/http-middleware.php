<?php

namespace Webimpress\HttpMiddlewareCompatibility;

// http-interop/http-middleware 0.3.0, 0.2.0, 0.1.1
if (interface_exists(\Interop\Http\Middleware\ServerMiddlewareInterface::class)
    && interface_exists(\Interop\Http\Middleware\DelegateInterface::class)
) {
    class_alias(
        \Interop\Http\Middleware\ServerMiddlewareInterface::class,
        MiddlewareInterface::class
    );

    class_alias(
        \Interop\Http\Middleware\DelegateInterface::class,
        HandlerInterface::class
    );

    if (method_exists(HandlerInterface::class, 'next')) {
        define(__NAMESPACE__ . '\HANDLER_METHOD', 'next');
    } else {
        define(__NAMESPACE__ . '\HANDLER_METHOD', 'process');
    }
    define(__NAMESPACE__ . '\HAS_RETURN_TYPE', false);
}

// http-interop/http-middleware 0.4.1
if (interface_exists(\Interop\Http\ServerMiddleware\MiddlewareInterface::class)
    && interface_exists(\Interop\Http\ServerMiddleware\DelegateInterface::class)
) {
    class_alias(
        \Interop\Http\ServerMiddleware\MiddlewareInterface::class,
        MiddlewareInterface::class
    );

    class_alias(
        \Interop\Http\ServerMiddleware\DelegateInterface::class,
        HandlerInterface::class
    );

    define(__NAMESPACE__ . '\HANDLER_METHOD', 'process');
    define(__NAMESPACE__ . '\HAS_RETURN_TYPE', false);
}

// http-interop/http-middleware 0.5.0 && http-interop/http-server-middleware 1.0.1
if (interface_exists(\Interop\Http\Server\MiddlewareInterface::class)
    && interface_exists(\Interop\Http\Server\RequestHandlerInterface::class)
) {
    class_alias(
        \Interop\Http\Server\MiddlewareInterface::class,
        MiddlewareInterface::class
    );

    class_alias(
        \Interop\Http\Server\RequestHandlerInterface::class,
        HandlerInterface::class
    );

    define(__NAMESPACE__ . '\HANDLER_METHOD', 'handle');

    if (PHP_VERSION_ID >= 70000) {
        $r = new \ReflectionMethod(MiddlewareInterface::class, 'process');
        define(__NAMESPACE__ . '\HAS_RETURN_TYPE', $r->hasReturnType());
    } else {
        define(__NAMESPACE__ . '\HAS_RETURN_TYPE', false);
    }
}
