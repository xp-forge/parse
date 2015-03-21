<?php namespace text\parse;

use lang\FormatException;

abstract class Rule extends \lang\Object {

  /**
   * Consume
   *
   * @param  [:text.parse.Rule] $rules
   * @param  text.parse.Tokens $tokens
   * @param  var[] $values
   * @return text.parse.Consumed
   */
  public abstract function consume($rules, $tokens, $values);

  /**
   * Evaluate rules and returns results
   *
   * @param  [:text.parse.Rule] $rules
   * @param  text.parse.Tokens $tokens
   * @return var
   * @throws lang.FormatException
   */
  public function evaluate($rules, $tokens) {
    $values= $this->consume($rules, $tokens, []);
    if ($values->matched()) {
      return $values->backing();
    } else {
      throw new FormatException($values->error());
    }
  }
}