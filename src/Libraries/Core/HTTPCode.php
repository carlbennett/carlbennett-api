<?php

namespace CarlBennett\API\Libraries\Core;

use \UnexpectedValueException;

final class HTTPCode
{
    public const _MIN = 100;
    public const _MAX = 999;

    public const CONTINUE = 100;
    public const SWITCHING_PROTOCOLS = 101;
    public const PROCESSING = 102;
    public const EARLY_HINTS = 103;

    public const OK = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const NON_AUTHORITATIVE_INFORMATION = 203;
    public const NO_CONTENT = 204;
    public const RESET_CONTENT = 205;
    public const PARTIAL_CONTENT = 206;
    public const MULTI_STATUS = 207;
    public const ALREADY_REPORTED = 208;

    public const MULTIPLE_CHOICES = 300;
    public const MOVED_PERMANENTLY = 301;
    public const FOUND = 302;
    public const SEE_OTHER = 303;
    public const NOT_MODIFIED = 304;
    public const USE_PROXY = 305;
    public const TEMPORARY_REDIRECT = 307;
    public const PERMANENT_REDIRECT = 308;

    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const PAYMENT_REQUIRED = 402;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const NOT_ACCEPTABLE = 406;
    public const PROXY_AUTHENTICATION_REQUIRED = 407;
    public const REQUEST_TIMEOUT = 408;
    public const CONFLICT = 409;
    public const GONE = 410;
    public const LENGTH_REQUIRED = 411;
    public const PRECONDITION_FAILED = 412;
    public const CONTENT_TOO_LARGE = 413;
    public const URI_TOO_LONG = 414;
    public const UNSUPPORTED_MEDIA_TYPE = 415;
    public const RANGE_NOT_SATISFIABLE = 416;
    public const EXPECTATION_FAILED = 417;
    public const I_AM_A_TEAPOT = 418;
    public const MISDIRECTED_REQUEST = 421;
    public const UNPROCESSABLE_CONTENT = 422;
    public const LOCKED = 423;
    public const FAILED_DEPENDENCY = 424;
    public const TOO_EARLY = 425;
    public const UPGRADE_REQUIRED = 426;
    public const PRECONDITION_REQUIRED = 428;
    public const TOO_MANY_REQUESTS = 429;
    public const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    public const UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    public const INTERNAL_SERVER_ERROR = 500;
    public const NOT_IMPLEMENTED = 501;
    public const BAD_GATEWAY = 502;
    public const SERVICE_UNAVAILABLE = 503;
    public const GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;
    public const VARIANT_ALSO_NEGOTIATES = 506;
    public const INSUFFICIENT_STORAGE = 507;
    public const LOOP_DETECTED = 508;
    public const NOT_EXTENDED = 510;
    public const NETWORK_AUTHENTICATION_REQUIRED = 511;

    public int $code = 0;

    public function __construct(int $code)
    {
        $this->setCode($code);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getName(): string
    {
        return self::getNameFromCode($this->getCode());
    }

    public static function getNameFromCode(int $code): string
    {
        switch ($code)
        {
            case self::CONTINUE: return 'Continue';
            case self::SWITCHING_PROTOCOLS: return 'Switching Protocols';
            case self::PROCESSING: return 'Processing';
            case self::EARLY_HINTS: return 'Early Hints';

            case self::OK: return 'OK';
            case self::CREATED: return 'Created';
            case self::ACCEPTED: return 'Accepted';
            case self::NON_AUTHORITATIVE_INFORMATION: return 'Non-Authoritative Information';
            case self::NO_CONTENT: return 'No Content';
            case self::RESET_CONTENT: return 'Reset Content';
            case self::PARTIAL_CONTENT: return 'Partial Content';
            case self::MULTI_STATUS: return 'Multi-Status';
            case self::ALREADY_REPORTED: return 'Already Reported';

            case self::MULTIPLE_CHOICES: return 'Multiple Choices';
            case self::MOVED_PERMANENTLY: return 'Moved Permanently';
            case self::FOUND: return 'Found';
            case self::SEE_OTHER: return 'See Other';
            case self::NOT_MODIFIED: return 'Not Modified';
            case self::USE_PROXY: return 'Use Proxy';
            case self::TEMPORARY_REDIRECT: return 'Temporary Redirect';
            case self::PERMANENT_REDIRECT: return 'Permanent Redirect';

            case self::BAD_REQUEST: return 'Bad Request';
            case self::UNAUTHORIZED: return 'Unauthorized';
            case self::PAYMENT_REQUIRED: return 'Payment Required';
            case self::FORBIDDEN: return 'Forbidden';
            case self::NOT_FOUND: return 'Not Found';
            case self::METHOD_NOT_ALLOWED: return 'Method Not Allowed';
            case self::NOT_ACCEPTABLE: return 'Not Acceptable';
            case self::PROXY_AUTHENTICATION_REQUIRED: return 'Proxy Authentication Required';
            case self::REQUEST_TIMEOUT: return 'Request Timeout';
            case self::CONFLICT: return 'Conflict';
            case self::GONE: return 'Gone';
            case self::LENGTH_REQUIRED: return 'Length Required';
            case self::PRECONDITION_FAILED: return 'Precondition Failed';
            case self::CONTENT_TOO_LARGE: return 'Content Too Large';
            case self::URI_TOO_LONG: return 'URI Too Long';
            case self::UNSUPPORTED_MEDIA_TYPE: return 'Unsupported Media Type';
            case self::RANGE_NOT_SATISFIABLE: return 'Range Not Satisfiable';
            case self::EXPECTATION_FAILED: return 'Expectation Failed';
            case self::I_AM_A_TEAPOT: return 'I\'m a teapot';
            case self::MISDIRECTED_REQUEST: return 'Misdirected Request';
            case self::UNPROCESSABLE_CONTENT: return 'Unprocessable Content';
            case self::LOCKED: return 'Locked';
            case self::FAILED_DEPENDENCY: return 'Failed Dependency';
            case self::TOO_EARLY: return 'Too Early';
            case self::UPGRADE_REQUIRED: return 'Upgrade Required';
            case self::PRECONDITION_REQUIRED: return 'Precondition Required';
            case self::TOO_MANY_REQUESTS: return 'Too Many Requests';
            case self::REQUEST_HEADER_FIELDS_TOO_LARGE: return 'Request Header Fields Too Large';
            case self::UNAVAILABLE_FOR_LEGAL_REASONS: return 'Unavailable For Legal Reasons';

            case self::INTERNAL_SERVER_ERROR: return 'Internal Server Error';
            case self::NOT_IMPLEMENTED: return 'Not Implemented';
            case self::BAD_GATEWAY: return 'Bad Gateway';
            case self::SERVICE_UNAVAILABLE: return 'Service Unavailable';
            case self::GATEWAY_TIMEOUT: return 'Gateway Timeout';
            case self::HTTP_VERSION_NOT_SUPPORTED: return 'HTTP Version Not Supported';
            case self::VARIANT_ALSO_NEGOTIATES: return 'Variant Also Negotiates';
            case self::INSUFFICIENT_STORAGE: return 'Insufficient Storage';
            case self::LOOP_DETECTED: return 'Loop Detected';
            case self::NOT_EXTENDED: return 'Not Extended';
            case self::NETWORK_AUTHENTICATION_REQUIRED: return 'Network Authentication Required';

            default: throw new UnexpectedValueException();
        }
    }

    public function setCode(int $code): void
    {
        if ($code < self::_MIN || $code > self::_MAX)
        {
            throw new UnexpectedValueException();
        }
        $this->code = $code;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
