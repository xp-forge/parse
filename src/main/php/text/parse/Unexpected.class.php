<?php namespace text\parse;

class Unexpected extends Consumed {
  
  public function __construct($message, $line) {
    $this->message= $message;
    $this->line= $line;
  }

  /** @return bool */
  public function matched() { return false; }

  /** @return string */
  public function error() { return $this->message; }

  /** @return string */
  public function toString() { return $this->getClassName().'["'.$this->message.'" at line '.$this->line.']'; }
}