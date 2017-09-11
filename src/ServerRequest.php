<?php

declare(strict_types=1);

namespace Moon\HttpMessage;

use DateTimeImmutable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * @var array
     */
    private $serverParams;
    /**
     * @var array
     */
    private $cookieParams;
    /**
     * @var array
     */
    private $attributes;

    public function __construct(array $serverParams, array $cookieParams, array $attributes, string $method, UriInterface $uri, StreamInterface $body, array $headers = [], string $requestTarget = null, string $protocolVersion = self::PROTOCOL_VERSION_1_1)
    {
        parent::__construct($method, $uri, $body, $headers, $requestTarget, $protocolVersion);
        $this->serverParams = $serverParams;
        $this->cookieParams = $cookieParams;
        $this->attributes = $attributes;
    }

    public static function fromGlobals(): ServerRequestInterface
    {
        $dt = new DateTimeImmutable();
        $dt->modify('oihadsihoasddahiso');
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookieParams): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->cookieParams = $cookieParams;

        return $clone;
    }

    public function getQueryParams(): array
    {
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
    }

    public function getUploadedFiles(): array
    {
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
    }

    public function getParsedBody(): ?array
    {
    }

    public function withParsedBody($data): ServerRequestInterface
    {
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): ServerRequestInterface
    {
        $attributes = $this->attributes;
        $attributes[$name] = $value;

        $clone = clone $this;
        $clone->attributes = $attributes;

        return $clone;
    }

    public function withoutAttribute($name): ServerRequestInterface
    {
        $attributes = $this->attributes;
        unset($attributes[$name]);

        $clone = clone $this;
        $clone->attributes = $attributes;

        return $clone;
    }
}
