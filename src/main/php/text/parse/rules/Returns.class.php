<?php namespace text\parse\rules;

use text\parse\Values;

class Returns extends \text\parse\Rule {
  private $value;

  /**
   * Consume
   *
   * @param  var $value Any value, or a function to apply
   */
  public function __construct($value) {
    $this->value= $value;
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
    if ($this->value instanceof \Closure) {
      $f= $this->value;
      return new Values($f($values, $tokens));
    } else {
      return new Values($this->value);
    }
  }

  public function toString() {
    return nameof($this).'@'.\xp::stringOf($this->value);
  }
}