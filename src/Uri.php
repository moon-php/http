<?php

declare(strict_types=1);

namespace Moon\HttpMessage;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    public function getScheme(): string
    {
    }

    public function getAuthority(): string
    {
    }

    public function getUserInfo(): string
    {
    }

    public function getHost(): string
    {
    }

    public function getPort(): ?int
    {
    }

    public function getPath(): string
    {
    }

    public function getQuery(): string
    {
    }

    public function getFragment(): string
    {
    }

    public function withScheme($scheme): UriInterface
    {
    }

    public function withUserInfo($user, $password = null): UriInterface
    {
    }

    public function withHost($host): UriInterface
    {
    }

    public function withPort($port): UriInterface
    {
    }

    public function withPath($path): UriInterface
    {
    }

    public function withQuery($query): UriInterface
    {
    }

    public function withFragment($fragment): UriInterface
    {
    }

    public function __toString(): string
    {
    }
}
