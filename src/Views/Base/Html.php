<?php /* vim: set colorcolumn=: */

namespace CarlBennett\API\Views\Base;

abstract class Html implements \CarlBennett\API\Interfaces\View
{
    public const MIMETYPE_HTML = 'text/html';

    /**
     * Provides the MIME-type that this View prints.
     *
     * @return string The MIME-type for this View class.
     */
    public static function mimeType(): string
    {
        return self::MIMETYPE_HTML . ';charset=utf-8';
    }
}
