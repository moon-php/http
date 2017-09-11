<?php

declare(strict_types=1);

namespace Moon\HttpMessage;

use Moon\HttpMessage\Exception\InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    public const PROTOCOL_VERSION_2_0 = '2.0';
    public const PROTOCOL_VERSION_1_1 = '1.1';
    public const PROTOCOL_VERSION_1_0 = '1.0';

    private const ALLOWED_PROTOCOL_VERSIONS = [
        self::PROTOCOL_VERSION_2_0,
        self::PROTOCOL_VERSION_1_1,
        self::PROTOCOL_VERSION_1_0,
    ];

    /**
     * @var string
     */
    protected $protocolVersion;
    /**
     * @var StreamInterface
     */
    protected $body;
    /**
     * @var array
     */
    protected $headers;

    public function __construct(
        StreamInterface $body,
        array $headers = [],
        string $protocolVersion = self::PROTOCOL_VERSION_1_1
    ) {
        $this->validateProtocolVersion($protocolVersion);

        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
        $this->headers = $this->transformHeaders($headers);
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($protocolVersion): self
    {
        $this->validateProtocolVersion($protocolVersion);
        $clone = clone $this;
        $clone->protocolVersion = $protocolVersion;

        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return \array_key_exists($name, $this->headers);
    }

    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return \implode(',', $this->getHeader($name));
    }

    public function withHeader($name, $value): self
    {
        $headers = $this->headers;
        $headers[] = $this->transformHeader($name, $value);

        $clone = clone $this;
        $clone->headers = $headers;

        return $clone;
    }

    public function withAddedHeader($name, $value): self
    {
        $headers = $this->headers;
        $headers[$name][] = \array_values($this->transformHeader($name, $value));

        $clone = clone $this;
        $clone->headers = $headers;

        return $clone;
    }

    public function withoutHeader($name): self
    {
        $headers = $this->headers;
        unset($headers[$name]);

        $clone = clone $this;
        $clone->headers = $headers;

        return $clone;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): self
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    private function validateProtocolVersion($protocolVersion): void
    {
        if (!\is_string($protocolVersion) ||
            !\in_array($protocolVersion, self::ALLOWED_PROTOCOL_VERSIONS, true)
        ) {
            throw new InvalidArgumentException(\sprintf(
                'Invalid protocol version, it can be one of those strings %s. %s given',
                \implode(',', self::ALLOWED_PROTOCOL_VERSIONS),
                $protocolVersion
            ));
        }
    }

    private function transformHeaders(array $headers): array
    {
        return \array_map([$this, 'transformHeader'], \array_keys($headers), $headers);
    }

    private function transformHeader($name, $values): array
    {
        if (!\is_string($name)) {
            throw new InvalidArgumentException(\sprintf(
                'Invalid headers, all names must be strings. %s given',
                $name
            ));
        }

        if (!\is_array($values)) {
            $values = [$values];
        }

        if ($values === \array_filter($values, '\is_string')) {
            throw new InvalidArgumentException(\sprintf(
                'Invalid headers values for %s, all the values must be strings. %s given',
                $name,
                \implode(',', $values)
            ));
        }

        return [$name => $values];
    }
}
