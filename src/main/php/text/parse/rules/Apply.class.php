<?php namespace text\parse\rules;

use text\parse\Values;

class Apply extends \text\parse\Rule {
  private $name;

  /**
   * Creates a new rule applying a rule by the specified name
   *
   * @param  string $name
   */
  public function __construct($name) {
    $this->name= $name;
  }

  public function code() {
    $id= $this->id();
    return '$rules[]= '.$id.'; goto R'.strtr($this->name, '-.', '__').'; } if ('.$id.' === $rule) {';
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
    return $rules->named($this->name)->consume($rules, $tokens, []);
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'(->'.$this->name.')';
  }
}