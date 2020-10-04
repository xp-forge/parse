<?php namespace text\parse\unittest;

use text\parse\rules\{Apply, Matches, OneOf, Repeated, Sequence, Token};
use text\parse\{Rules, Syntax, Tokenized};
use unittest\{Test, TestCase};

class ArrayTest extends TestCase {
  private $syntax;

  /**
   * Declares syntax
   */
  public function setUp() {
    $this->syntax= new class() extends Syntax {
      public function rules() { return new Rules([
        new Sequence([new Apply('expr')], function($values) { return $values[0]; }),
        'expr' => new OneOf([
          new Matches([
            T_LNUMBER => function($values) { return (int)$values[0]; },
            '[' => new Sequence(
              [new Repeated(new Apply('expr'), new Token(',')), new Token(']')],
              function($values) { return $values[1]; }
            )
          ]),
          new Sequence(
            [new Token(T_STRING), new Token(T_DOUBLE_COLON), new Token(T_CLASS)],
            function($values) { return $values[0]; }
          ),
          new Sequence(
            [new Token(T_STRING)],
            function($values) { return $values[0]; }
          )
        ])
      ]); }
    };
  }

  #[Test]
  public function empty_array() {
    $tokens= new Tokenized('[]');
    $this->assertEquals([], $this->syntax->parse($tokens));
  }

  #[Test]
  public function array_of_ints() {
    $tokens= new Tokenized('[1, 2, 3]');
    $this->assertEquals([1, 2, 3], $this->syntax->parse($tokens));
  }

  #[Test]
  public function array_of_strings() {
    $tokens= new Tokenized('[hello, world]');
    $this->assertEquals(['hello', 'world'], $this->syntax->parse($tokens));
  }

  #[Test]
  public function array_of_classes() {
    $tokens= new Tokenized('[Object::class, Throwable::class]');
    $this->assertEquals(['Object', 'Throwable'], $this->syntax->parse($tokens));
  }

  #[Test]
  public function nested_array() {
    $tokens= new Tokenized('[[hello]]');
    $this->assertEquals([['hello']], $this->syntax->parse($tokens));
  }

  #[Test]
  public function nested_empty_array() {
    $tokens= new Tokenized('[hello, []]');
    $this->assertEquals(['hello', []], $this->syntax->parse($tokens));
  }

  #[Test]
  public function nested_arrays() {
    $tokens= new Tokenized('[[hello, world], [1, 2]]');
    $this->assertEquals([['hello', 'world'], [1, 2]], $this->syntax->parse($tokens));
  }
}