<?php

declare(strict_types=1);

namespace Moon\HttpMessage;

use Fig\Http\Message\StatusCodeInterface;
use Moon\HttpMessage\Exception\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends Message implements ResponseInterface
{
    private const ALLOWED_STATUS_CODE = [
        StatusCodeInterface::STATUS_CONTINUE,
        StatusCodeInterface::STATUS_SWITCHING_PROTOCOLS,
        StatusCodeInterface::STATUS_PROCESSING,
        StatusCodeInterface::STATUS_OK,
        StatusCodeInterface::STATUS_CREATED,
        StatusCodeInterface::STATUS_ACCEPTED,
        StatusCodeInterface::STATUS_NON_AUTHORITATIVE_INFORMATION,
        StatusCodeInterface::STATUS_NO_CONTENT,
        StatusCodeInterface::STATUS_RESET_CONTENT,
        StatusCodeInterface::STATUS_PARTIAL_CONTENT,
        StatusCodeInterface::STATUS_MULTI_STATUS,
        StatusCodeInterface::STATUS_ALREADY_REPORTED,
        StatusCodeInterface::STATUS_IM_USED,
        StatusCodeInterface::STATUS_MULTIPLE_CHOICES,
        StatusCodeInterface::STATUS_MOVED_PERMANENTLY,
        StatusCodeInterface::STATUS_FOUND,
        StatusCodeInterface::STATUS_SEE_OTHER,
        StatusCodeInterface::STATUS_NOT_MODIFIED,
        StatusCodeInterface::STATUS_USE_PROXY,
        StatusCodeInterface::STATUS_RESERVED,
        StatusCodeInterface::STATUS_TEMPORARY_REDIRECT,
        StatusCodeInterface::STATUS_PERMANENT_REDIRECT,
        StatusCodeInterface::STATUS_BAD_REQUEST,
        StatusCodeInterface::STATUS_UNAUTHORIZED,
        StatusCodeInterface::STATUS_PAYMENT_REQUIRED,
        StatusCodeInterface::STATUS_FORBIDDEN,
        StatusCodeInterface::STATUS_NOT_FOUND,
        StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED,
        StatusCodeInterface::STATUS_NOT_ACCEPTABLE,
        StatusCodeInterface::STATUS_PROXY_AUTHENTICATION_REQUIRED,
        StatusCodeInterface::STATUS_REQUEST_TIMEOUT,
        StatusCodeInterface::STATUS_CONFLICT,
        StatusCodeInterface::STATUS_GONE,
        StatusCodeInterface::STATUS_LENGTH_REQUIRED,
        StatusCodeInterface::STATUS_PRECONDITION_FAILED,
        StatusCodeInterface::STATUS_PAYLOAD_TOO_LARGE,
        StatusCodeInterface::STATUS_URI_TOO_LONG,
        StatusCodeInterface::STATUS_UNSUPPORTED_MEDIA_TYPE,
        StatusCodeInterface::STATUS_RANGE_NOT_SATISFIABLE,
        StatusCodeInterface::STATUS_EXPECTATION_FAILED,
        StatusCodeInterface::STATUS_IM_A_TEAPOT,
        StatusCodeInterface::STATUS_MISDIRECTED_REQUEST,
        StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
        StatusCodeInterface::STATUS_LOCKED,
        StatusCodeInterface::STATUS_FAILED_DEPENDENCY,
        StatusCodeInterface::STATUS_UPGRADE_REQUIRED,
        StatusCodeInterface::STATUS_PRECONDITION_REQUIRED,
        StatusCodeInterface::STATUS_TOO_MANY_REQUESTS,
        StatusCodeInterface::STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE,
        StatusCodeInterface::STATUS_UNAVAILABLE_FOR_LEGAL_REASONS,
        StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
        StatusCodeInterface::STATUS_NOT_IMPLEMENTED,
        StatusCodeInterface::STATUS_BAD_GATEWAY,
        StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE,
        StatusCodeInterface::STATUS_GATEWAY_TIMEOUT,
        StatusCodeInterface::STATUS_VERSION_NOT_SUPPORTED,
        StatusCodeInterface::STATUS_VARIANT_ALSO_NEGOTIATES,
        StatusCodeInterface::STATUS_INSUFFICIENT_STORAGE,
        StatusCodeInterface::STATUS_LOOP_DETECTED,
        StatusCodeInterface::STATUS_NOT_EXTENDED,
        StatusCodeInterface::STATUS_NETWORK_AUTHENTICATION_REQUIRED,
    ];
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var string
     */
    private $reasonPhrase;

    public function __construct(
        StreamInterface $body,
        int $statusCode = StatusCodeInterface::STATUS_OK,
        string $reasonPhrase = '',
        array $headers = [],
        string $protocolVersion = self::PROTOCOL_VERSION_1_1
    ) {
        $this->validateStatusCode($statusCode);
        $this->validateReasonPhrase($reasonPhrase);
        parent::__construct($body, $headers, $protocolVersion);
        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($statusCode, $reasonPhrase = ''): ResponseInterface
    {
        $this->validateStatusCode($statusCode);
        $this->validateReasonPhrase($reasonPhrase);

        $clone = clone $this;
        $clone->statusCode = $statusCode;
        $clone->reasonPhrase = $reasonPhrase;

        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function validateStatusCode($statusCode): void
    {
        if (!\in_array(self::ALLOWED_STATUS_CODE, $statusCode, true)) {
            throw new InvalidArgumentException(
                \sprintf(
                    "The response status MUST be a valid status code integer, '%s' given",
                    $statusCode
                )
            );
        }
    }

    private function validateReasonPhrase($reasonPhrase): void
    {
        if (!\is_string($reasonPhrase)) {
            throw new InvalidArgumentException(
                \sprintf(
                    "The response reason phrase MUST be a string, '%s' given",
                    $reasonPhrase
                )
            );
        }
    }
}
