<?php namespace text\parse\unittest\rules;

use text\parse\Tokenized;
use text\parse\rules\Token;
use text\parse\rules\OneOf;
use text\parse\rules\Sequence;

class OneOfTest extends \unittest\TestCase {
  private $fixture;

  /**
   * Creates fixture
   *
   * @return void
   */
  public function setUp() {
    $this->fixture= new OneOf([
      new Sequence([new Token(T_STRING), new Token(T_STRING)], function($values) { return ['first' => $values]; }),
      new Sequence([new Token(T_STRING)], function($values) { return ['second' => $values]; }),
    ]);
  }

  #[@test]
  public function first_rule_matches() {
    $this->assertEquals(['first' => ['a', 'b']], $this->fixture->consume([], new Tokenized('a b'), [])->backing());
  }

  #[@test]
  public function second_rule_matches() {
    $this->assertEquals(['second' => ['a']], $this->fixture->consume([], new Tokenized('a'), [])->backing());
  }

  #[@test]
  public function no_rules_matches() {
    $message= 'Unexpected T_LNUMBER<1>, expecting one of ';
    $this->assertEquals($message, substr(
      $this->fixture->consume([], new Tokenized('1'), [])->error(),
      0,
      strlen($message)
    ));
  }
}