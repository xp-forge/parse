<?php namespace text\parse;

/**
 * Return value from a consume() call.
 *
 * @see  xp://text.parse.Rule#consume
 * @see  xp://text.parse.Values
 * @see  xp://text.parse.Unmatched
 * @see  xp://text.parse.Unexpected
 */
abstract class Consumed {

  /**
   * Returns whether the rule matched
   *
   * @return bool
   */
  public abstract function matched();
}