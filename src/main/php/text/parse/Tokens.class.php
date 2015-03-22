<?php namespace text\parse;

/**
 * Base class for tokenizers. Subclasses implement the next() and name()
 * methods.
 *
 * @test  xp://text.parse.unittest.TokensTest
 */
abstract class Tokens extends \lang\Object {
  private $token= null;
  private $tokens= [];
  private $position= 0;

  /**
   * Returns next token, or NULL
   *
   * @return var
   */
  protected abstract function next();

  /**
   * Returns the name of a given token
   *
   * @param  var $token Either an integer ID or a character
   * @return string
   */
  protected abstract function name($token);

  /**
   * Returns a string representation of a given token
   *
   * @param  var $token Either a single character or an array: `[ID, text, line]`
   * @return string
   */
  public function nameOf($token) {
    return is_array($token) ? $this->name($token[0]).'<'.$token[1].'>' : $this->name($token);
  }

  /**
   * Returns current token.
   *
   * @return var
   */
  public function token() {
    if (null === $this->token) {
      $this->forward();
    }
    return $this->token;
  }

  /**
   * Returns current line.
   *
   * @return int
   */
  public function line() {
    return 1;
  }

  /**
   * Returns current token number.
   *
   * @return int
   */
  public function position() {
    return max(0, $this->position - 1);
  }

  /**
   * Forwards tokens
   *
   * @return void
   */
  public function forward() {
    if ($this->position >= sizeof($this->tokens)) {
      $this->token= $this->next();
      $this->tokens[]= $this->token;
    } else {
      $this->token= $this->tokens[$this->position];
    }
    // echo ">>$this->position ", $this->nameOf($this->token[0]), is_array($this->token) ? '<'.$this->token[1].'>' : '', "\n";
    $this->position++;
  }

  /**
   * Goes back to a given token number, previously captured via position().
   *
   * @param  int $to
   * @return void
   */
  public function backup($to) {
    // echo "<<$to\n";
    $this->position= $to;
    $this->forward();
  }
}
