<?php namespace text\parse;

/**
 * Indicates a consume() yielded unexpected tokens.
 *
 * @see  xp://text.parse.Rule#consume
 */
class Unexpected extends Consumed {
  private $message, $line;

  /**
   * Constructor
   *
   * @param  string $message
   * @param  int $line
   */
  public function __construct($message, $line) {
    $this->message= $message;
    $this->line= $line;
  }

  /** @return bool */
  public function matched() { return false; }

  /** @return string */
  public function error() { return $this->message; }

  /** @return string */
  public function toString() { return nameof($this).'["'.$this->message.'" at line '.$this->line.']'; }
}