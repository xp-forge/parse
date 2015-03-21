<?php namespace text\parse\rules;

use text\parse\Values;

class Apply extends \text\parse\Rule {
  private $name, $func;

  public function __construct($name, $func= null) {
    $this->name= $name;
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
  public function consume($rules, $tokens, $values) {
    $result= $rules->named($this->name)->consume($rules, $tokens, []);
    if ($f= $this->func && $result->matched()) {
      $values[]= $result->backing();
      return new Values($f($values, $tokens));
    } else {
      return $result;
    }
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName().'(->'.$this->name.')';
  }
}