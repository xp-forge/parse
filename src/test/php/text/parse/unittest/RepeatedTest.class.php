<?php namespace text\parse\unittest;

use text\parse\Tokenized;
use text\parse\rules\Repeated;
use text\parse\rules\Token;
use text\parse\rules\Sequence;

class RepeatedTest extends \unittest\TestCase {
  private $fixture;

  /**
   * Creates fixture
   *
   * @return void
   */
  public function setUp() {
    $this->fixture= new Repeated(new Sequence(
      [new Token(T_USE), new Token(T_STRING), new Token(';')],
      function($values) { return $values[1]; }
    ));
  }

  #[@test]
  public function one_use_statement() {
    $tokens= new Tokenized('use a;');
    $this->assertEquals(['a'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function two_use_statements() {
    $tokens= new Tokenized('use a; use b;');
    $this->assertEquals(['a', 'b'], $this->fixture->consume([], $tokens, [])->backing());
  }
}