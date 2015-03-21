<?php namespace text\parse;

/**
 * Indicates a consume() call was unmatched.
 *
 * @see  xp://text.parse.Rule#consume
 */
class Unmatched extends Consumed {
  private $rule, $cause;

  /**
   * Constructor
   *
   * @param  text.parse.Rule $rule
   * @param  text.parse.Consumed $cause
   */
  public function __construct($rule, $cause) {
    $this->rule= $rule;
    $this->cause= $cause;
  }

  /** @return bool */
  public function matched() { return false; }

  /** @return string */
  public function error() { return 'Umatched rule '.$this->rule->toString()."]\n  Caused by ".$this->cause->toString(); }

  /** @return string */
  public function toString() { return $this->getClassName().'@'.$this->error(); }
}