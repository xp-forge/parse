<?php namespace text\parse\unittest\rules;

use text\parse\Tokenized;
use text\parse\rules\Matches;
use unittest\{Test, Values};

class MatchTest extends \unittest\TestCase {

  #[Test, Values([['1', 1], ['-1', -1], ['1.5', 1.5], ['-1.5', -1.5], ['true', true]])]
  public function constants_and_numbers($input, $outcome) {
    $rule= new Matches([
      T_STRING  => function($values) { return constant($values[0]); },
      T_DNUMBER => function($values) { return (double)$values[0]; },
      T_LNUMBER => function($values) { return (int)$values[0]; },
      '-'       => new Matches([
        T_DNUMBER => function($values) { return -(double)$values[0]; },
        T_LNUMBER => function($values) { return -(int)$values[0]; },
      ])
    ]);
    $this->assertEquals($outcome, $rule->consume([], new Tokenized($input), [])->backing());
  }

  #[Test]
  public function mismatched_numbers() {
    $rule= new Matches([
      T_DNUMBER => function($values) { /* Intentionally empty */ },
      T_LNUMBER => function($values) { /* Intentionally empty */ }
    ]);
    $this->assertEquals(
      'Unexpected T_STRING<hello>, expecting to match T_DNUMBER, T_LNUMBER',
      $rule->consume([], new Tokenized('hello'), [])->error()
    );
  }
}