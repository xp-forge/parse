<?php namespace text\parse\unittest;

use text\parse\Rules;
use text\parse\Tokenized;
use text\parse\rules\Repeated;
use text\parse\rules\Sequence;
use text\parse\rules\Token;
use text\parse\rules\Apply;
use text\parse\rules\AnyOf;
use text\parse\rules\Collect;

/**
 * Verifies example on front page works
 *
 * @see   https://github.com/xp-forge/parse/blob/master/README.md
 */
class MapTest extends \unittest\TestCase {
  private $syntax;

  /**
   * Declares syntax
   */
  public function setUp() {
    $this->syntax= newinstance('text.parse.Syntax', [], [
      'rules' => function() { return new Rules([
        new Repeated(
          new Sequence([new Token(T_STRING), new Token(':'), new Apply('val')], function($values) {
            return [$values[0] => $values[2]];
          }),
          new Token(','),
          Collect::$AS_MAP
        ),
        'val' => new AnyOf([
          T_CONSTANT_ENCAPSED_STRING => function($values) { return substr($values[0], 1, -1); },
          T_STRING                   => function($values) { return constant($values[0]); },
          T_DNUMBER                  => function($values) { return (double)$values[0]; },
          T_LNUMBER                  => function($values) { return (int)$values[0]; }
        ])
      ]); }
    ]);
  }

  #[@test]
  public function readme_example() {
    $this->assertEquals(
      ['a' => 1, 'b' => 2.0, 'c' => true, 'd' => 'D'],
      $this->syntax->parse(new Tokenized('a: 1, b: 2.0, c: true, d: "D"'))
    );
  }
}