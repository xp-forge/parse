<?php namespace text\parse;

class Values extends Consumed {
  private $backing;

  public function __construct($values) {
    $this->backing= $values;
  }

  /** @return bool */
  public function matched() { return true; }

  /** @return var */
  public function backing() { return $this->backing; }
}