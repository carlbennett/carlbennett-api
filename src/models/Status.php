<?php

namespace CarlBennett\API\Models;

use \CarlBennett\API\Libraries\Model;

class Status extends Model {

  public $remote_address;
  public $remote_geoinfo;
  public $remote_user_agent;
  public $timestamp;
  public $version_info;

  public function __construct() {
    parent::__construct();
    $this->remote_address    = null;
    $this->remote_geoinfo    = null;
    $this->remote_user_agent = null;
    $this->timestamp         = null;
    $this->version_info      = null;
  }

}
