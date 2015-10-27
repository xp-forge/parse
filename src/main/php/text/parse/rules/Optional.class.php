<?php namespace text\parse\rules;

use text\parse\Values;

class Optional extends \text\parse\Rule {
  private $rule, $default;
  
  public function __construct($rule, $default= null) {
    $this->rule= $rule;
    $this->default= $default;
  }

  public function code() {
    return $this->rule->code().' if (!$result->matched()) {
      $result= new \text\parse\Values('.var_export($this->default, 1).');
    }';
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
    $result= $this->rule->consume($rules, $stream, $values);
    if ($result->matched()) {
      return $result;
    } else {
      return new Values($this->default);
    }
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