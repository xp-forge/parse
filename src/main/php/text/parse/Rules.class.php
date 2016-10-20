<?php namespace text\parse;

use lang\ElementNotFoundException;
use lang\IllegalArgumentException;

/**
 * Holds the start rule and optionally more named rules.
 *
 * @see   xp://text.parse.Syntax#rules
 * @test  xp://text.parse.unittest.RulesTest
 */
class Rules extends \lang\Object {
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

  public function code() {
    $code= '$errors= []; $rules= [0]; while (null !== ($rule= array_pop($rules))) {';
    foreach ($this->named as $name => $rule) {
      if (0 === $name) {
        $code.= 'if (0 === $rule) { '.$rule->code().'}';
      } else {
        $code.= 'else if (\''.$name.'\' === $rule) { R'.strtr($name, '-.', '__').':'.$rule->code().'}';
      }
    }
    return $code.'} return $errors ?: $result;';
  }


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