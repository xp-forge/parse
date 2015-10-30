<?php namespace text\parse;

use lang\FormatException;

abstract class Rule extends \lang\Object {
  private static $var= 0;

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
   * Generates code
   *
   * @return string
   */
  public function code() {
    return 'throw new \lang\MethodNotImplementedException(\'Not overridden in '.nameof($this).'\', \'code\');';
  }

  /**
   * Generates a unique variable
   *
   * @return string
   */
  protected function var() { return '$_'.(self::$var++); }

  /**
   * Generates a unique id
   *
   * @return string
   */
  protected function id() { return self::$var++; }

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