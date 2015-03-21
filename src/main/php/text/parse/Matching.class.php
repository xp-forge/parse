<?php namespace text\parse;

class Matching extends Rule {
  private $tokens;
  
  public function __construct($tokens) {
    $this->tokens= $tokens;
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
    $consumed= [];
    while (in_array($tokens->token()[0], $this->tokens)) {
      $consumed[]= $tokens->token()[1];
      $tokens->forward();
    }
    return new Values($consumed);
  }

  private function nameOf($token) { return is_int($token) ? token_name($token) : '`'.$token.'`'; }

  public function toString() {
    return $this->getClassName().'['.implode(' | ', array_map([$this, 'nameOf'], $this->tokens)).']';
  }
}
