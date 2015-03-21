<?php namespace text\parse\rules;

use text\parse\Rule;
use text\parse\Values;

/**
 * Repeats a given rules 0..n times; until it no longer fully matches.
 * 
 * @test  xp://text.parse.unittest.RepeatedTest
 * @test  xp://text.parse.unittest.RepeatedAddTest
 * @test  xp://text.parse.unittest.RepeatedMapTest
 */
class Repeated extends Rule {
  public static $MAP, $ADD;

  static function __static() {
    self::$MAP= function(&$values, $value) { $values[key($value)]= current($value); };
    self::$ADD= function(&$values, $value) { $values[]= $value; };
  }

  /**
   * Creates a new repeated rule
   *
   * @param  text.parse.Rule $rule
   * @param  text.parse.Rule $delimiter
   * @param  php.Closure $combine
   */
  public function __construct(Rule $rule, Rule $delimiter= null, $combine= null) {
    $this->rule= $rule;
    $this->delimiter= $delimiter;
    $this->combine= $combine ?: self::$ADD;
  }

  /**
   * Consume
   *
   * @param  [:text.parse.Rule] $rules
   * @param  text.parse.Tokens $tokens
   * @param  var[] $values
   * @return text.parse.Consumed
   */
  public function consume($rules, $tokens, $values) {
    $f= $this->combine;
    $continue= true;

    do {
      $result= $this->rule->consume($rules, $tokens, []);
      if ($continue= $result->matched()) {
        $f($values, $result->backing());
        if ($this->delimiter) {
          $continue= $this->delimiter->consume($rules, $tokens, [])->matched();
        }
      }
    } while ($continue);

    return new Values($values);
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName().'(->'.$this->rule->toString().')';
  }
}