<?php namespace text\parse\unittest;

use text\parse\Tokenized;
use text\parse\Rules;
use text\parse\rules\Sequence;
use text\parse\rules\Returns;
use text\parse\rules\Apply;
use text\parse\rules\Token;
use text\parse\rules\RecursionOf;
use text\parse\rules\AnyOf;

class CalculatorTest extends \unittest\TestCase {
  private $syntax;

  /**
   * Declares syntax
   */
  public function setUp() {
    $this->syntax= newinstance('text.parse.Syntax', [], [
      'rules' => function() { return new Rules([
        new Apply('expr'),
        'expr' => new RecursionOf(
          [[
            '*'       => function($values) { return $values[0] * $values[2]; },
            '/'       => function($values) { return $values[0] / $values[2]; },
          ], [
            '+'       => function($values) { return $values[0] + $values[2]; },
            '-'       => function($values) { return $values[0] - $values[2]; },
          ]],
          new AnyOf([
            T_LNUMBER => function($values) { return (int)$values[0]; },
            T_DNUMBER => function($values) { return (double)$values[0]; },
            '-'       => new Sequence([new Apply('expr')], function($values) { return -1 * $values[1]; }),
            '+'       => new Sequence([new Apply('expr')], function($values) { return 1 * $values[1]; }),
            '('       => new Sequence([new Apply('expr'), new Token(')')], function($values) { return $values[1]; }),
          ])
        )
      ]); }
    ]);
  }

  #[@test]
  public function integer_by_itself() {
    $tokens= new Tokenized('1');
    $this->assertEquals(1, $this->syntax->parse($tokens));
  }

  #[@test]
  public function negative_integer_by_itself() {
    $tokens= new Tokenized('-1');
    $this->assertEquals(-1, $this->syntax->parse($tokens));
  }

  #[@test]
  public function positive_integer_by_itself() {
    $tokens= new Tokenized('+1');
    $this->assertEquals(1, $this->syntax->parse($tokens));
  }

  #[@test]
  public function adding_two_integers() {
    $tokens= new Tokenized('1 + 2');
    $this->assertEquals(3, $this->syntax->parse($tokens));
  }

  #[@test]
  public function adding_three_integers() {
    $tokens= new Tokenized('1 + 2 + 3');
    $this->assertEquals(6, $this->syntax->parse($tokens));
  }

  #[@test]
  public function decimal_by_itself() {
    $tokens= new Tokenized('1.5');
    $this->assertEquals(1.5, $this->syntax->parse($tokens));
  }

  #[@test]
  public function dividing_an_integer_by_a_decimal() {
    $tokens= new Tokenized('1 / 0.5');
    $this->assertEquals(2.0, $this->syntax->parse($tokens));
  }

  #[@test]
  public function precedence_of_multiplication() {
    $tokens= new Tokenized('1 + 2 * 3 - 4');
    $this->assertEquals(3, $this->syntax->parse($tokens));
  }

  #[@test]
  public function precedence_of_division() {
    $tokens= new Tokenized('1 + 10 / 5 * 2');
    $this->assertEquals(5, $this->syntax->parse($tokens));
  }

  #[@test, @values([
  #  '1 + (2 * 3) - 4',
  #  '(1 + 2) * 3 - 4',
  #  '1 + 2 * (3 - 4)',
  #  '(1 + 2) * (3 - 4)',
  #  '(1 + 2 * 3 - 4)',
  #  '1 + (2 * 3 - 4)',
  #  '1 + (2 * (3 - 4))'
  #])]
  public function precedence_using_braces($string) {
    $tokens= new Tokenized($string);
    $this->assertEquals(eval('return '.$string.';'), $this->syntax->parse($tokens));
  }
}