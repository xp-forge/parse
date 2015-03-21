<?php namespace text\parse\unittest;

use text\parse\Repeated;
use text\parse\Token;
use text\parse\Sequence;
use text\parse\Optional;

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
      Repeated::$ADD
    );
  }

  #[@test]
  public function number_by_itself() {
    $tokens= new StringInput('1');
    $this->assertEquals(['1'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function two_numbers() {
    $tokens= new StringInput('1, 2');
    $this->assertEquals(['1', '2'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function two_numbers_and_dangling_comma_at_end() {
    $tokens= new StringInput('1, 2, ');
    $this->assertEquals(['1', '2'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function inside_another_rule() {
    $tokens= new StringInput('f(1, 2);');
    $rule= new Sequence(
      [new Token(T_STRING), new Token('('), $this->fixture, new Token(')'), new Token(';')],
      function($values) { return [$values[0] => $values[2]]; }
    );
    $this->assertEquals(['f' => ['1', '2']], $rule->consume([], $tokens, [])->backing());
  }
}