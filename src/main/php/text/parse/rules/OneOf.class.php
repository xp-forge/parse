<?php namespace text\parse\rules;

use text\parse\Rule;
use text\parse\Unexpected;
use lang\IllegalArgumentException;

/**
 * Tries to match one of the given rules. Returns the values of the first
 * rule to match.
 *
 * If you're able to determine by the first token which rule should match
 * further on, use the `Match` rule instead as it offers better performance.
 *
 * @see   xp://text.parse.rules.Match
 * @test  xp://text.parse.unittest.rules.OneOfTest
 */
class OneOf extends Rule {
  private $rules;

  /**
   * Creates a new instance
   *
   * @param  text.parse.Rule[] $rules
   * @throws lang.IllegalArgumentException
   */
  public function __construct(array $rules) {
    if (empty($rules)) {
      throw new IllegalArgumentException('Rules may not be empty');
    }
    $this->rules= $rules;
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
    $token= $tokens->token();
    foreach ($this->rules as $try) {
      $result= $try->consume($rules, $tokens, []);
      if ($result->matched()) return $result;
    }

    return new Unexpected('Unexpected '.$tokens->nameOf($token), $tokens->line());
  }
}