<?php namespace text\parse\unittest;

use text\parse\rules\{Apply, Collect, Matches, Repeated, Sequence, Token};
use text\parse\{Rules, Syntax, Tokenized};
use unittest\{Test, TestCase};

/**
 * Verifies example on front page works
 *
 * @see   https://github.com/xp-forge/parse/blob/master/README.md
 */
class MapTest extends TestCase {
  private $syntax;

  /**
   * Declares syntax
   */
  public function setUp() {
    $this->syntax= new class extends Syntax {
      public function rules() { return new Rules([
        new Repeated(
          new Sequence([new Token(T_STRING), new Token(':'), new Apply('val')], function($values) {
            return [$values[0] => $values[2]];
          }),
          new Token(','),
          Collect::$AS_MAP
        ),
        'val' => new Matches([
          T_CONSTANT_ENCAPSED_STRING => function($values) { return substr($values[0], 1, -1); },
          T_STRING                   => function($values) { return constant($values[0]); },
          T_DNUMBER                  => function($values) { return (double)$values[0]; },
          T_LNUMBER                  => function($values) { return (int)$values[0]; }
        ])
      ]); }
    };
  }

  #[Test]
  public function readme_example() {
    $this->assertEquals(
      ['a' => 1, 'b' => 2.0, 'c' => true, 'd' => 'D'],
      $this->syntax->parse(new Tokenized('a: 1, b: 2.0, c: true, d: "D"'))
    );
  }
}