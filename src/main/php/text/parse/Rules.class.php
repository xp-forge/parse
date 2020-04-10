<?php namespace text\parse;

use lang\{ElementNotFoundException, IllegalArgumentException};

/**
 * Holds the start rule and optionally more named rules.
 *
 * @see   xp://text.parse.Syntax#rules
 * @test  xp://text.parse.unittest.RulesTest
 */
class Rules {
  private $named;

  /**
   * Creates a new rules instance
   *
   * @param  [:text.parse.Rule] $named
   * @throws lang.IllegalArgumentException
   */
  public function __construct(array $named) {
    if (!isset($named[0])) {
      throw new IllegalArgumentException('No start rule defined');
    }
    $this->named= $named;
  }

  /** @return text.parse.Rule */
  public function start() { return $this->named[0]; }

  /**
   * Returns a rule by a given name
   *
   * @param   string $name
   * @return  text.parse.Rule
   * @throws  lang.ElementNotFoundException
   */
  public function named($name) {
    if (!isset($this->named[$name])) {
      throw new ElementNotFoundException('No such rule "'.$name.'"');
    }
    return $this->named[$name];
  }
}