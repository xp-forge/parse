<?php namespace text\parse\unittest;

use text\parse\Optional;
use text\parse\Token;
use text\parse\Sequence;

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
    $tokens= new StringInput('a');
    $this->assertEquals(['a' => null], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function statement_with_optional_semicolon() {
    $tokens= new StringInput('a;');
    $this->assertEquals(['a' => ';'], $this->fixture->consume([], $tokens, [])->backing());
  }
}