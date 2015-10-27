<?php namespace text\parse\rules;

use text\parse\Values;
use text\parse\Unmatched;

class Sequence extends \text\parse\Rule {
  private $rules, $func;

  public function __construct($rules, $func= null) {
    $this->rules= $rules;
    $this->func= $func;
  }

  public function code() {
    $code= '';
    foreach ($this->rules as $rule) {
      $code.= $rule->code();
      $code.= '$values[]= $result;';
    }
    $code.= $this->func;
    return $code;
  }

  /**
   * Consume
   *
   * @param  [:text.parse.Rule] $rules
   * @param  text.parse.Tokens $tokens
   * @param  var[] $values
   * @return text.parse.Consumed
   */
  public function consume($rules, $stream, $values) {
    $begin= $stream->position();
    foreach ($this->rules as $rule) {
      $result= $rule->consume($rules, $stream, []);
      if ($result->matched()) {
        $values[]= $result->backing();
      } else {
        $stream->backup($begin);
        return new Unmatched($rule, $result);
      }
    }

    if ($f= $this->func) {
      return new Values($f($values, $stream));
    } else {
      return new Values($values);
    }
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName().'@'.\xp::stringOf($this->rules);
  }
}