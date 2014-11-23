<?php

namespace CarlBennett\API\Libraries;

class HTTPHeader implements \Serializable {

  public function __construct($name, $value) {
    $this->name = $name;
    $this->value = $value;
  }

  public function getName() {
    return $this->name;
  }

  public function getValue() {
    return $this->value;
  }

  public function __tostring() {
    return $this->name . ": " . $value . "\n";
  }

  public function serialize() {
    return serialize([$this->name, $this->value]);
  }

  public function unserialize($data) {
    $this->name = $data[0];
    $this->value = $data[1];
  }

}
