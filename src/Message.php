<?php

namespace Moon\HttpMessage;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use function array_merge;
use function in_array;
use function is_array;

class Message implements MessageInterface
{
    /**
     * @var array
     */
    protected $versions = [];
    /**
     * @var string
     */
    protected $version;
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var StreamInterface
     */
    protected $body;


    /**
     *{@inheritdoc}
     */
    public function getProtocolVersion(): string
    {
        return $this->version;
    }

    /**
     *{@inheritdoc}
     */
    public function withProtocolVersion($version): self
    {
        $clone = clone $this;
        $clone->version = $version;

        return $clone;
    }

    /**
     *{@inheritdoc}
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     *{@inheritdoc}
     */
    public function hasHeader($name): bool
    {
        return in_array($name, $this->headers, true);
    }

    /**
     *{@inheritdoc}
     */
    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    /**
     *{@inheritdoc}
     */
    public function getHeaderLine($name): string
    {
        // TODO: Implement getHeaderLine() method.
    }

    /**
     *{@inheritdoc}
     */
    public function withHeader($name, $value): self
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $clone = clone $this;
        $clone->headers[$name] = $value;

        return $clone;
    }

    /**
     *{@inheritdoc}
     */
    public function withAddedHeader($name, $value): self
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $clone = clone $this;
        if (isset($clone->headers[$name])) {
            $value = array_merge($clone->headers[$name], $value);
        }

        $clone->headers[$name] = $value;

        return $clone;
    }

    /**
     *{@inheritdoc}
     */
    public function withoutHeader($name): self
    {
        $clone = clone $this;
        if (isset($clone->headers[$name])) {
            unset($clone->headers[$name]);
        }

        return $clone;
    }

    /**
     *{@inheritdoc}
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     *{@inheritdoc}
     */
    public function withBody(StreamInterface $body): self
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }
}