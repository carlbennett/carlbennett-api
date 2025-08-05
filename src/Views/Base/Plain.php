<?php /* vim: set colorcolumn=: */

namespace CarlBennett\API\Views\Base;

abstract class Plain implements \CarlBennett\API\Interfaces\View
{
    public const MIMETYPE_PLAIN = 'text/plain';

    /**
     * Provides the MIME-type that this View prints.
     *
     * @return string The MIME-type for this View class.
     */
    public static function mimeType(): string
    {
        return self::MIMETYPE_PLAIN . ';charset=utf-8';
    }
}
