<?php

namespace Moon\HttpMessage;

use Exception;
use Fig\Http\Message\RequestMethodInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use const true;
use function in_array;
use function strtolower;

class Request extends Message implements RequestInterface
{
    /**
     * @var array VALID_METHODS
     */
    private const VALID_METHODS = [
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

    /**
     * @var array
     */
    private $requestTargets = [
        'origin-form',
        'absolute-form',
        'authority-form',
        'asterisk-form',
    ];

    /**
     * @var string
     */
    private $requestTarget;
    /**
     * @var string
     */
    private $method;
    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * Create a Request using global variables
     *
     * @param StreamInterface|null $body
     *
     * @return Request
     */
    public static function fromGlobal(StreamInterface $body = null): self
    {
        $request = new self();
        $request->headers = [];
        // $request->body = $body ?? new StreamInterface();
        // TODO: Add other ones
        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function withRequestTarget($requestTarget): self
    {
        $requestTarget = strtolower($requestTarget);
        if (!in_array($requestTarget, $this->requestTargets, true)) {
            throw new Exception('INVALID REQUEST TARGET!!!');
        }

        $clone = clone $this;
        $clone->requestTarget = $requestTarget;
        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function withMethod($method): self
    {
        if (!in_array($method, self::VALID_METHODS, true)) {
            throw new Exception("INVALID METHOD REQUEST!!!");
        }

        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false): self
    {
        $clone = clone $this;
        $clone->uri = $uri;

        return $clone;
    }
}