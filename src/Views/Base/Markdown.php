<?php /* vim: set colorcolumn=: */

namespace CarlBennett\API\Views\Base;

abstract class Markdown implements \CarlBennett\API\Interfaces\View
{
    public const MIMETYPE_MARKDOWN = 'text/markdown';

    /**
     * Provides the MIME-type that this View prints.
     *
     * @return string The MIME-type for this View class.
     */
    public static function mimeType() : string
    {
        return self::MIMETYPE_MARKDOWN . ';charset=utf-8';
    }
}
