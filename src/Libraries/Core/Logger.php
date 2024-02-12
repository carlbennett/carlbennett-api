<?php

namespace CarlBennett\API\Libraries\Core;

class Logger
{
    public static string $additionalHtmlHeader = '';
    public static string $additionalHtmlFooter = '';

    private function __construct() {}

    /**
     * Logs custom metrics to Application Performance Monitors (APMs).
     */
    public static function logMetric(string $key, mixed $value): bool
    {
        $success = true;

        if (\extension_loaded('newrelic'))
        {
            $added = \newrelic_custom_metric($key, $value);
            if (!$added) $success = false;
        }

        return $success;
    }

    /**
     * Registers Application Performance Monitors (APMs).
     */
    public static function registerAPMs(): void
    {
        if (\extension_loaded('newrelic'))
        {
            \newrelic_disable_autorum();
            self::$additionalHtmlHeader .= \newrelic_get_browser_timing_header();
            self::$additionalHtmlFooter .= \newrelic_get_browser_timing_footer();
        }
    }
}
