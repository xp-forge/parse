<?php namespace text\parse\unittest\rules;

use text\parse\Tokenized;
use text\parse\rules\{Optional, Sequence, Token};
use unittest\Test;

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

  #[Test]
  public function statement() {
    $tokens= new Tokenized('a');
    $this->assertEquals(['a' => null], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[Test]
  public function statement_with_optional_semicolon() {
    $tokens= new Tokenized('a;');
    $this->assertEquals(['a' => ';'], $this->fixture->consume([], $tokens, [])->backing());
  }
}