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
    $code= '$rules= ["start"]; while (null !== ($rule= array_shift($rules))) { echo "@$rule: "; var_dump($tokens->token()); switch ($rule) {';
    foreach ($this->named as $name => $rule) {
      $code.= "\ncase ".(0 === $name ? '"start"' : '"'.$name.'"').': echo "<$rule>\n"; $values= []; '.$rule->code().' echo "</$rule>\n"; break;';
    }
    return $code.'}} return $result;';
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