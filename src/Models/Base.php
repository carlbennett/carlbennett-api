<?php

namespace CarlBennett\API\Models;

class Base implements \CarlBennett\API\Interfaces\Model, \JsonSerializable
{
    public int $_responseCode = \CarlBennett\API\Libraries\Core\HTTPCode::INTERNAL_SERVER_ERROR;
    public array $_responseHeaders = [
        'Cache-Control' => 'max-age=0,no-cache,no-store', // disables cache in the browser for all PHP pages by default.
        'X-Content-Type-Options' => 'nosniff', // Prevent browsers from incorrectly detecting non-scripts as scripts.
        'X-Frame-Options' => 'DENY', // DENY tells the browser to prevent archaic frame/iframe embeds of all pages including from ourselves (see also: SAMEORIGIN).
        'X-XSS-Protection' => '1;mode=block', // Block pages from loading when they detect reflected XSS attacks.
    ];

    /**
     * Implements the JSON serialization function from the JsonSerializable interface.
     */
    public function jsonSerialize(): mixed
    {
        return [];
    }
}
