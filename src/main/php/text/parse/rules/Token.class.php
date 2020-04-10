<?php namespace text\parse\rules;

use text\parse\{Unexpected, Values};

class Token extends \text\parse\Rule {
  private $token;

  /**
   * Creates a new token rule
   *
   * @param  var $token Either an integer id or a single character
   */
  public function __construct($token) {
    $this->token= $token;
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

    if (null !== $token && $token[0] === $this->token) {
      $tokens->forward();
      return new Values(is_array($token) ? $token[1] : $token);
    } else {
      return new Unexpected(
        sprintf(
          'Unexpected %s, expecting %s',
          is_array($token) ? token_name($token[0]) : $token,
          is_int($this->token) ? token_name($this->token) : $this->token
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
    return nameof($this).'[`'.(is_int($this->token) ? token_name($this->token) : $this->token).'`]';
  }
}