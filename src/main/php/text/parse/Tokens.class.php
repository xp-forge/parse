<?php namespace text\parse;

abstract class Tokens extends \lang\Object {
  private $token= null;
  private $tokens= [];
  private $position= 0;

  /** @return var */
  protected abstract function next();

  public function token() {
    if (null === $this->token) {
      $this->forward();
    }
    return $this->token;
  }

  public function line() {
    return 1;
  }

  public function position() {
    return max(0, $this->position - 1);
  }

  public function forward() {
    if ($this->position >= sizeof($this->tokens)) {
      $this->token= $this->next();
      $this->tokens[]= $this->token;
    } else {
      $this->token= $this->tokens[$this->position];
    }
    // echo ">>$this->position ", $this->nameOf($this->token[0]), is_array($this->token) ? '<'.$this->token[1].'>' : '', "\n";
    $this->position++;
    return true;
  }

  public function backup($to) {
    // echo "<<$to\n";
    $this->position= $to;
    $this->token= null;
  }

  public function nameOf($token) {
    return is_int($token) ? token_name($token) : '`'.$token.'`';
  }
}
