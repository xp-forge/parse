<?php namespace text\parse;

use lang\ElementNotFoundException;

/**
 * Holds the start rule and optionally more named rules.
 *
 * @see   xp://text.parse.Syntax#rules
 * @test  xp://text.parse.unittest.RulesTest
 */
class Rules extends \lang\Object {
  private $start, $named;

  /**
   * Creates a new rules instance
   *
   * @param  text.parse.Rule $start
   * @param  [:text.parse.Rule] $named
   */
  public function __construct(Rule $start, array $named= []) {
    $this->start= $start;
    $this->named= $named;
  }

  /** @return text.parse.Rule */
  public function start() { return $this->start; }

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