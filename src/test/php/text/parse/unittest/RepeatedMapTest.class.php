<?php namespace text\parse\unittest;

use text\parse\rules\Repeated;
use text\parse\rules\Token;
use text\parse\rules\Sequence;
use text\parse\rules\Optional;

class RepeatedMapTest extends \unittest\TestCase {
  private $fixture;

  /**
   * Creates fixture
   *
   * @return void
   */
  public function setUp() {
    $this->fixture= new Repeated(
      new Sequence(
        [new Token(T_STRING), new Token(':'), new Token(T_STRING)],
        function($values) { return [$values[0] => $values[2]]; }
      ),
      new Token(','),
      Repeated::$MAP
    );
  }

  #[@test]
  public function pair_by_itself() {
    $tokens= new StringInput('a : b');
    $this->assertEquals(['a' => 'b'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function two_pairs() {
    $tokens= new StringInput('a : b, c: d');
    $this->assertEquals(['a' => 'b', 'c' => 'd'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function two_pairs_and_dangling_comma_at_end() {
    $tokens= new StringInput('a : b, c: d, ');
    $this->assertEquals(['a' => 'b', 'c' => 'd'], $this->fixture->consume([], $tokens, [])->backing());
  }

  #[@test]
  public function inside_another_rule() {
    $tokens= new StringInput('set{a : b};');
    $rule= new Sequence(
      [new Token(T_STRING), new Token('{'), $this->fixture, new Token('}'), new Token(';')],
      function($values) { return [$values[0] => $values[2]]; }
    );
    $this->assertEquals(['set' => ['a' => 'b']], $rule->consume([], $tokens, [])->backing());
  }
}