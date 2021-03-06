<?php namespace text\parse\rules;

use text\parse\Values;

/**
 * A list of tokens, combined in any way: `Tokens(['.', T_LNUMBER])` will
 * for example match IP addresses, but also three dots, just numbers or
 * a number and a dot. High performance alternative to `Repeated` and/or
 * `RecursionOf` rules.
 *
 * @test  xp://text.parse.unittest.rules.TokensTest
 */
class Tokens extends \text\parse\Rule {
  private $tokens;
  
  /**
   * Creates a rule matching tokens combined of other tokens
   *
   * @param  var... $tokens
   */
  public function __construct(... $tokens) {
    $this->tokens= array_flip($tokens);
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
    while (null !== ($t= $tokens->token()) && isset($this->tokens[$t[0]])) {
      $token= $tokens->token();
      $values[]= is_array($token) ? $token[1] : $token;
      $tokens->forward();
    }
    return new Values($values);
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return nameof($this).'['.implode(' | ', array_keys($this->tokens)).']';
  }
}