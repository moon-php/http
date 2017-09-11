<?php

declare(strict_types=1);

namespace Moon\HttpMessage;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface
{
    public function getStream(): StreamInterface
    {
    }

    public function moveTo($targetPath): void
    {
    }

    public function getSize(): ?int
    {
    }

    public function getError(): int
    {
    }

    public function getClientFilename(): ?string
    {
    }

    public function getClientMediaType(): ?string
    {
    }
}
