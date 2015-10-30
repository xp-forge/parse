<?php namespace text\parse\rules;

use text\parse\Rule;
use text\parse\Unexpected;

/**
 * Matches tokens in a lookup map.
 *
 * @test  xp://text.parse.unittest.rules.MatchTest
 */
class Match extends Rule {
  private $lookup;

  /**
   * Creates a new match instance. The lookup map is defined as follows:
   *
   * - The keys form the tokens to match on, either numeric IDs or single characters.
   * - The value can either be a text.parse.Rule instance (in which case the given
   *   rule is matched), a function (in which case the token value is passed to it) or 
   *   any value (in which case this value is simply returned).
   *
   * @param  [:var] $lookup
   */
  public function __construct($lookup) {
    $this->lookups= $lookup;
    foreach ($lookup as $token => $handler) {
      $this->lookup[$token]= $handler instanceof Rule ? $handler : new Returns($handler);
    }
  }

  public function code() {
    $var= $this->var();

    $code= '$token= $tokens->token();';
    foreach ($this->lookups as $token => $handler) {
      $code.= 'if ('.$token.' === $token[0]) {
        $tokens->forward();
        '.$var.'= [is_array($token) ? $token[1] : $token]; '.
        strtr($handler, ['$values' => $var]).'
      }';
    }
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
  public function consume($rules, $tokens, $values) {
    $case= $tokens->token();
    if (isset($this->lookup[$case[0]])) {
      $tokens->forward();
      return $this->lookup[$case[0]]->consume($rules, $tokens, [is_array($case) ? $case[1] : $case]);
    } else {
      return new Unexpected(
        sprintf(
          'Unexpected %s, expecting to match %s',
          $tokens->nameOf($case),
          implode(', ', array_map([$tokens, 'nameOf'], array_keys($this->lookup)))
        ),
        $tokens->line()
      );
    }
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName().'@'.\xp::stringOf($this->lookup);
  }
}