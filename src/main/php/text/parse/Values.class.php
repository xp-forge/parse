<?php namespace text\parse;

/**
 * Indicates a consume() call returns values.
 *
 * @see  xp://text.parse.Rule#consume
 */
class Values extends Consumed {
  private $backing;

  /**
   * Constructor
   *
   * @param  var $values
   */
  public function __construct($values) {
    $this->backing= $values;
  }

  /** @return bool */
  public function matched() { return true; }

  /** @return var */
  public function backing() { return $this->backing; }
}