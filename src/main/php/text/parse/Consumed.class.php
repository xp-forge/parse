<?php namespace text\parse;

abstract class Consumed extends \lang\Object {

  /**
   * Returns whether the rule matched
   *
   * @return bool
   */
  public abstract function matched();
}