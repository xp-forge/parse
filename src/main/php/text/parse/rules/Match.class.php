<?php namespace text\parse\rules;

use text\parse\Rule;
use text\parse\Unexpected;

class Match extends Rule {
  private $detect, $try;

  public function __construct($detect, $try= []) {
    foreach ($detect as $token => $handler) {
      $this->detect[$token]= $handler instanceof Rule ? $handler : new Returns($handler);
    }
    $this->try= $try;
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
    if (isset($this->detect[$case[0]])) {
      $tokens->forward();
      return $this->detect[$case[0]]->consume($rules, $tokens, [is_array($case) ? $case[1] : $case]);
    } else {
      $begin= $tokens->position();
      foreach ($this->try as $try) {
        $result= $try->consume($rules, $tokens, []);
        if ($result->matched()) return $result;
        $tokens->backup($begin);
      }
    }

    return new Unexpected(
      sprintf(
        'Unexpected %s, expecting any of %s or %s',
        $tokens->nameOf(is_array($case) ? $case[0] : $case),
        implode(', ', array_map([$tokens, 'nameOf'], array_keys($this->detect))),
        implode(', ', array_map(['xp', 'stringOf'], $this->try))
      ),
      $tokens->line()
    );
  }
}