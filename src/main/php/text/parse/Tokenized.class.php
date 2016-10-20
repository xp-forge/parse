<?php namespace text\parse;

/**
 * Tokens implementation based on PHP's built-in tokenizer.
 *
 * @see   php://token_get_all
 * @test  xp://text.parse.unittest.TokensTest
 */
class Tokenized extends Tokens {
  private $input;

  /**
   * Creates a new input from a given string
   *
   * @param  string $string
   */
  public function __construct($string) {
    $this->input= array_slice(token_get_all('<?='.$string), 1);
  }

  /**
   * Returns next token, or NULL
   *
   * @return var
   */
  protected function next() {
    do {
      $token= array_shift($this->input);
    } while ($token && T_WHITESPACE === $token[0]);

    return $token;
  }

  /**
   * Returns the name of a given token
   *
   * @param  var $token Either an integer ID or a character
   * @return string
   */
  protected function name($token) {
    return is_int($token) ? token_name($token) : '`'.$token.'`';
  }

  public function toString() {
    return nameof($this).'('.sizeof($this->input).' tokens)';
  }
}
