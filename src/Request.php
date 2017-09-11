<?php

declare(strict_types=1);

namespace Moon\HttpMessage;

use Fig\Http\Message\RequestMethodInterface;
use Moon\HttpMessage\Exception\InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /**
     * @var string
     */
    private $method;
    /**
     * @var UriInterface
     */
    private $uri;
    /**
     * @var string|null
     */
    private $requestTarget;

    private $allowedHttpMethods = [
        RequestMethodInterface::METHOD_HEAD,
        RequestMethodInterface::METHOD_GET,
        RequestMethodInterface::METHOD_POST,
        RequestMethodInterface::METHOD_PUT,
        RequestMethodInterface::METHOD_PATCH,
        RequestMethodInterface::METHOD_DELETE,
        RequestMethodInterface::METHOD_PURGE,
        RequestMethodInterface::METHOD_OPTIONS,
        RequestMethodInterface::METHOD_TRACE,
        RequestMethodInterface::METHOD_CONNECT,
    ];

    public function __construct(
        string $method,
        UriInterface $uri,
        StreamInterface $body,
        array $headers = [],
        string $requestTarget = null,
        string $protocolVersion = self::PROTOCOL_VERSION_1_1
    ) {
        $this->method = $this->transformMethod($method);
        $this->uri = $uri;
        $this->requestTarget = $requestTarget;
        parent::__construct($body, $headers, $protocolVersion);
    }

    public function getRequestTarget(): string
    {
        if (null !== $this->requestTarget) {
            return $this->requestTarget;
        }

        if (null === $this->uri) {
            return '/';
        }

        if ('' === ($target = $this->uri->getPath())) {
            $target = '/';
        }

        if ('' !== ($queryString = $this->uri->getQuery())) {
            $target .= '?'.$queryString;
        }

        return $target;
    }

    public function withRequestTarget($requestTarget): RequestInterface
    {
        if ('*' !== $requestTarget || !\filter_var($requestTarget, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(
                \sprintf(
                    "The request target MUST be compliant to %s specification, '%s' given",
                    'https://tools.ietf.org/html/rfc7230#section-5.3',
                    $requestTarget
                ));
        }

        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): RequestInterface
    {
        $method = $this->transformMethod($method);
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if (false === $preserveHost) {
            return $clone;
        }

        $hostHeader = $this->getHeader('host');
        $hostUri = $this->uri->getHost();

        if (empty($hostHeader) && '' !== $hostUri) {
            $clone->headers = $clone->withHeader('host', $hostUri)->getHeaders();
        }

        return $clone;
    }

    private function transformMethod($method): string
    {
        $method = \mb_strtoupper($method);
        if (!\in_array($method, $this->allowedHttpMethods, true)) {
            throw new InvalidArgumentException(
                \sprintf(
                    "The request method MUST be a valid one, '%s' given",
                    $method
                )
            );
        }

        return $method;
    }
}
