<?php namespace text\parse\unittest\rules;

use text\parse\Tokenized;
use text\parse\rules\Repeated;
use text\parse\rules\Token;
use text\parse\rules\Sequence;
use text\parse\rules\Optional;
use text\parse\rules\Collect;

class RepeatedAddTest extends \unittest\TestCase {
  private $fixture;

  /**
   * Creates fixture
   *
   * @return void
   */
  public function setUp() {
    $this->fixture= new Repeated(
      new Sequence([new Token(T_LNUMBER)], function($values) { return $values[0]; }),
      new Token(','),
      Collect::$IN_ARRAY
    );
  }

  #[@test]
  public function number_by_itself() {
    $tokens= new Tokenized('1');
    $this->assertEquals(['1'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function two_numbers() {
    $tokens= new Tokenized('1, 2');
    $this->assertEquals(['1', '2'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function two_numbers_and_dangling_comma_at_end() {
    $tokens= new Tokenized('1, 2, ');
    $this->assertEquals(['1', '2'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function inside_another_rule() {
    $tokens= new Tokenized('f(1, 2);');
    $rule= new Sequence(
      [new Token(T_STRING), new Token('('), $this->fixture, new Token(')'), new Token(';')],
      function($values) { return [$values[0] => $values[2]]; }
    );
    $this->assertEquals(['f' => ['1', '2']], $rule->consume([], $tokens, [])->backing());
  }
}