<?php

declare(strict_types=1);

namespace Moon\HttpMessage;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    public static function fromString(string $string): StreamInterface
    {
    }

    public function __toString(): string
    {
    }

    public function close(): void
    {
    }

    public function detach()
    {
    }

    public function getSize(): ?int
    {
    }

    public function tell(): int
    {
    }

    public function eof(): bool
    {
    }

    public function isSeekable(): bool
    {
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
    }

    public function rewind(): void
    {
    }

    public function isWritable(): bool
    {
    }

    public function write($string): int
    {
    }

    public function isReadable(): bool
    {
    }

    public function read($length): string
    {
    }

    public function getContents(): string
    {
    }

    public function getMetadata($key = null)
    {
    }
}
