<?php namespace text\parse\rules;

use text\parse\{Unmatched, Values};

class Sequence extends \text\parse\Rule {
  private $rules, $func;

  public function __construct($rules, $func= null) {
    $this->rules= $rules;
    $this->func= $func;
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
    return nameof($this).'@'.\xp::stringOf($this->rules);
  }
}