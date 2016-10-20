<?php namespace text\parse\rules;

use text\parse\Values;
use text\parse\Unexpected;

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

  public function code() {
    return '$token= $tokens->token(); if ($token[0] === '.(is_int($this->token) ? $this->token : '"'.$this->token.'"').') {
      $tokens->forward();
      $result= is_array($token) ? $token[1] : $token;
    } else {
      $errors[]= new \text\parse\Unexpected(sprintf(
        "Unexpected `%s`, expected `'.(is_int($this->token) ? token_name($this->token) : $this->token).'` [state %s]",
        is_array($token) ? token_name($token[0]) : $token,
        $rule
      ), $tokens->line());
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
  public function consume($rules, $tokens, $values) {
    $token= $tokens->token();

    if ($token[0] === $this->token) {
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