<?php namespace text\parse\unittest;

use text\parse\Tokenized;
use text\parse\rules\Optional;
use text\parse\rules\Token;
use text\parse\rules\Sequence;

class OptionalTest extends \unittest\TestCase {
  private $fixture;

  /**
   * Creates fixture
   *
   * @return void
   */
  public function setUp() {
    $this->fixture= new Sequence(
      [new Token(T_STRING), new Optional(new Token(';'))],
      function($values) { return [$values[0] => $values[1]]; }
    );
  }

  #[@test]
  public function statement() {
    $tokens= new Tokenized('a');
    $this->assertEquals(['a' => null], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function statement_with_optional_semicolon() {
    $tokens= new Tokenized('a;');
    $this->assertEquals(['a' => ';'], $this->fixture->consume([], $tokens, [])->backing());
  }
}