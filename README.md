# http-middleware-compatibility

[![Build Status](https://travis-ci.org/webimpress/http-middleware-compatibility.svg?branch=master)](https://travis-ci.org/webimpress/http-middleware-compatibility)

## Purpose

The purpose of the library is to deliver consistent interface for different
versions of [`http-interop/http-middleware`](https://github.com/http-interop/http-middleware)
which implements [Draft of PSR-15 HTTP Middleware](https://github.com/php-fig/fig-standards/tree/master/proposed/http-middleware).

Many projects currently use different version of library
`http-interop/http-middleware` and updating to newest version requires usually
major release. The library lets consumers of your component decide what version
of `http-interop/http-middleware` they want to use and allow them to migrate to
the latest version at any time.

## Usage

Your middleware should implement interface `Webimpress\HttpMiddlewareCompatibility\MiddlewareInterface`,
and for delegator/request handler you should use interface
`Webimpress\HttpMiddlewareCompatibility\HandlerInterface`.

```php
<?php

namespace MyNamespace;

use Psr\Http\Message\ServerRequestInterface;
use Webimpress\HttpMiddlewareCompatibility\HandlerInterface;
use Webimpress\HttpMiddlewareCompatibility\MiddlewareInterface;

class MyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        // ...
    }
}
```

Both interfaces are just aliases. It allows your middleware to work with
currently installed version of `http-interop/http-middleware` library.

### Delegate/Handler method

The method name was changed across the different versions of
`http-interop/http-middleware`. First the method was called `next` (release
0.1.1), then `process` (from 0.2 - 0.4.1) and then `handle` (in 0.5.0).
To accomplish all of them we provide constant
`Webimpress\HttpMiddlewareCompatibility\HANDLER_METHOD`, which has
appropriate value depends on used version of http-middleware.
Here is an example how you can use it in your middleware:

```php
<?php

namespace MyNamespace;

use Psr\Http\Message\ServerRequestInterface;
use Webimpress\HttpMiddlewareCompatibility\HandlerInterface;
use Webimpress\HttpMiddlewareCompatibility\MiddlewareInterface;

use const Webimpress\HttpMiddlewareCompatibility\HANDLER_METHOD;

class MyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        return $handler->{HANDLER_METHOD}($request);
    }
}
```

That's it! Now consumers of your component can decide what version of
`http-interop/http-middleware` they want to use.
