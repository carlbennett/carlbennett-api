<?php

namespace CarlBennett\API\Libraries;

use \StdClass;

class VersionInfo {

  const VERSION_INFO_FILE = '../etc/.rsync-version';

  public static $version;

  /**
   * Block instantiation of this object.
   */
  private function __construct() {}

  public static function get() {
    $versions = new StdClass();

    $versions->api = self::getVersion();
    $versions->php = phpversion();

    return $versions;
  }

  private static function getVersion() {
    if ( !file_exists( self::VERSION_INFO_FILE )) {
      return null;
    }

    $buffer = file_get_contents( self::VERSION_INFO_FILE );

    if ( empty( $buffer )) {
      return null;
    }

    // The deploy script uses "\n", don't use PHP_EOL here.
    $buffer = explode( "\n", $buffer );

    return $buffer;
  }

}
