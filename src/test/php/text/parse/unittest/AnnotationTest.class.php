<?php namespace text\parse\unittest;

use text\parse\Rules;
use text\parse\Tokenized;
use text\parse\rules\Sequence;
use text\parse\rules\Apply;
use text\parse\rules\Token;
use text\parse\rules\ListOf;
use text\parse\rules\Repeated;
use text\parse\rules\AnyOf;
use text\parse\rules\Optional;
use text\parse\rules\Collect;

class AnnotationTest extends \unittest\TestCase {
  private $syntax;

  /**
   * Declares syntax
   */
  public function setUp() {
    $this->syntax= newinstance('text.parse.Syntax', [], [
      'rules' => function() { return new Rules([
        new Sequence(
          [new Token('['), new Apply('annotations'), new Token(']')],
          function($values) { return $values[1]; }
        ),
        'annotations' => new Repeated(new Apply('annotation'), new Token(','), Collect::$IN_MAP),
        'annotation'  => new Sequence(
          [new Token('@'), new Token(T_STRING), new Optional(new Apply('value'))],
          function($values) { return [$values[1] => $values[2]]; }
        ),
        'value'       => new Sequence(
          [
            new Token('('),
            new AnyOf([
              T_CONSTANT_ENCAPSED_STRING => function($values) { return substr($values[0], 1, -1); },
              T_STRING                   => function($values) { return constant($values[0]); },
              T_DNUMBER                  => function($values) { return (double)$values[0]; },
              T_LNUMBER                  => function($values) { return (int)$values[0]; }
            ]),
            new Token(')')
          ],
          function($values) { return $values[1]; }
        )
      ]); }
    ]);
  }

  #[@test]
  public function test_annotation() {
    $tokens= new Tokenized('[@test]');
    $this->assertEquals(['test' => null], $this->syntax->parse($tokens));
  }

  #[@test]
  public function test_and_slow_annotations() {
    $tokens= new Tokenized('[@test, @slow]');
    $this->assertEquals(['test' => null, 'slow' => null], $this->syntax->parse($tokens));
  }

  #[@test]
  public function rule_annotation_with_string_value() {
    $tokens= new Tokenized('[@rule("admin")]');
    $this->assertEquals(['rule' => 'admin'], $this->syntax->parse($tokens));
  }

  #[@test]
  public function access_annotation_with_constant_value() {
    $tokens= new Tokenized('[@access(MODIFIER_PUBLIC)]');
    $this->assertEquals(['access' => MODIFIER_PUBLIC], $this->syntax->parse($tokens));
  }

  #[@test]
  public function limit_annotation_with_constant_value() {
    $tokens= new Tokenized('[@limit(1.4)]');
    $this->assertEquals(['limit' => 1.4], $this->syntax->parse($tokens));
  }
}