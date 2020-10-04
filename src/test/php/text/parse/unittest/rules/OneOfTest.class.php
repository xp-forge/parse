<?php namespace text\parse\unittest\rules;

use lang\IllegalArgumentException;
use text\parse\Tokenized;
use text\parse\rules\{OneOf, Sequence, Token};
use unittest\{Expect, Test};

class OneOfTest extends \unittest\TestCase {

  /**
   * Creates fixture
   *
   * @return text.parse.rules.OneOf
   */
  public function fixture() {
    return new OneOf([
      new Sequence([new Token(T_STRING), new Token(T_STRING)], function($values) { return ['first' => $values]; }),
      new Sequence([new Token(T_STRING)], function($values) { return ['second' => $values]; }),
    ]);
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function cannot_create_with_empty_rules() {
    new OneOf([]);
  }

  #[Test]
  public function first_rule_matches() {
    $this->assertEquals(['first' => ['a', 'b']], $this->fixture()->consume([], new Tokenized('a b'), [])->backing());
  }

  #[Test]
  public function second_rule_matches() {
    $this->assertEquals(['second' => ['a']], $this->fixture()->consume([], new Tokenized('a'), [])->backing());
  }

  #[Test]
  public function no_rules_matches() {
    $message= 'Unexpected T_LNUMBER<1>';
    $this->assertEquals($message, $this->fixture()->consume([], new Tokenized('1'), [])->error());
  }
}