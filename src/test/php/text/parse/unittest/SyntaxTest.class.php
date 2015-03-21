<?php namespace text\parse\unittest;

use text\parse\Rules;
use text\parse\Returns;

class SyntaxTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    newinstance('text.parse.Syntax', [], [
      'rules' => function() { return new Rules([new Returns(null)]); }
    ]);
  }

  #[@test]
  public function parse() {
    $syntax= newinstance('text.parse.Syntax', [], [
      'rules' => function() { return new Rules([new Returns('Test')]); }
    ]);
    $tokens= newinstance('text.parse.Tokens', [], [
      'next' => function() { return null; },
      'name' => function($token) { return 'undefined'; }
    ]);
    $this->assertEquals('Test', $syntax->parse($tokens));
  }
}