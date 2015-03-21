<?php namespace text\parse\unittest;

class StringInput extends \text\parse\Tokens {
  private $input;

  /**
   * Creates a new input from a given string
   *
   * @param  string $string
   */
  public function __construct($string) {
    $this->input= array_slice(token_get_all('<?='.$string), 1);
  }

  /** @return var */
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
}