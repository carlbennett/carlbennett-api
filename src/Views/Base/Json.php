<?php /* vim: set colorcolumn=: */

namespace CarlBennett\API\Views\Base;

abstract class Json implements \CarlBennett\API\Interfaces\View
{
    public const MIMETYPE_JSON = 'application/json';

    /**
     * Gets the standard flags to call with json_encode() in subclasses.
     *
     * @return int The flags to pass to json_encode().
     */
    public static function jsonFlags() : int
    {
        $PRETTY = (
            \php_sapi_name() == 'cli' || \CarlBennett\API\Libraries\Core\VersionInfo::isBrowser()
        ) ? \JSON_PRETTY_PRINT : 0;
        return \JSON_BIGINT_AS_STRING | \JSON_PRESERVE_ZERO_FRACTION | $PRETTY | \JSON_THROW_ON_ERROR;
    }

    /**
     * Provides the MIME-type that this View prints.
     *
     * @return string The MIME-type for this View class.
     */
    public static function mimeType() : string
    {
        return self::MIMETYPE_JSON . ';charset=utf-8';
    }
}
