<?php

namespace CarlBennett\API\Libraries\Software;

use \CarlBennett\API\Libraries\Exceptions\SoftwareLicenseNotFoundException
  as SLNFException;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\DatabaseDriver;
use \DateTime;
use \DateTimeZone;
use \InvalidArgumentException;
use \PDO;
use \PDOException;
use \StdClass;

class License {

  private $active;
  private $email_address;
  private $id;
  private $issue_date;
  private $label;
  private $paypal_transaction;

  public function __construct($data) {
    if (is_string($data) && strlen($data) == 36) {
      $this->active             = null;
      $this->email_address      = null;
      $this->id                 = $data;
      $this->issue_date         = null;
      $this->label              = null;
      $this->paypal_transaction = null;
      $this->refresh();
    } else if ($data instanceof StdClass) {
      self::normalize($data);
      $this->active             = $data->active;
      $this->email_address      = $data->email_address;
      $this->id                 = $data->id;
      $this->issue_date         = $data->issue_date;
      $this->label              = $data->label;
      $this->paypal_transaction = $data->paypal_transaction;
    } else {
      throw new InvalidArgumentException('Cannot use data argument');
    }
  }

  public function getActive() {
    return ($this->active ? true : false);
  }

  public function getEmailAddress() {
    return $this->email_address;
  }

  public function getId() {
    return $this->id;
  }

  public function getIssueDate() {
    if (is_null($this->issue_date)) {
      return null;
    } else {
      return new DateTime($this->issue_date, new DateTimeZone('UTC'));
    }
  }

  public function getLabel() {
    return $this->label;
  }

  public function getPayPalTransaction() {
    return $this->paypal_transaction;
  }

  protected static function normalize(StdClass &$data) {
    $data->active        = (int)    $data->active;
    $data->email_address = (string) $data->email_address;
    $data->id            = (string) $data->id;
    $data->issue_date    = (string) $data->issue_date;
    $data->label         = (string) $data->label;

    if (!is_null($data->paypal_transaction))
      $data->paypal_transaction = (string) $data->paypal_transaction;

    return true;
  }

  public function refresh() {
    if (!isset(Common::$database)) {
      Common::$database = DatabaseDriver::getDatabaseObject();
    }
    try {
      $stmt = Common::$database->prepare('
        SELECT
          `active`,
          `email_address`,
          `id`,
          `issue_date`,
          `label`,
          `paypal_transaction`
        FROM `software_licenses`
        WHERE `id` = :id
        LIMIT 1;
      ');
      $stmt->bindParam(":id", $this->id, PDO::PARAM_STR);
      if (!$stmt->execute()) {
        throw new QueryException('Cannot refresh software license object');
      } else if ($stmt->rowCount() == 0) {
        throw new SLNFException('Software license not found: ' . $this->id);
      }
      $row = $stmt->fetch(PDO::FETCH_OBJ);
      $stmt->closeCursor();
      self::normalize($row);
      $this->active             = $row->active;
      $this->email_address      = $row->email_address;
      $this->id                 = $row->id;
      $this->issue_date         = $row->issue_date;
      $this->label              = $row->label;
      $this->paypal_transaction = $row->paypal_transaction;
      return true;
    } catch (PDOException $e) {
      throw new QueryException('Cannot refresh software license object');
    }
    return false;
  }

}
