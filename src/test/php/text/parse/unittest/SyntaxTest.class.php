<?php namespace text\parse\unittest;

use text\parse\rules\Returns;
use text\parse\{Rules, Syntax, Tokens};
use unittest\TestCase;

class SyntaxTest extends TestCase {

  #[@test]
  public function can_create() {
    new class() extends Syntax {
      public function rules() { return new Rules([new Returns(null)]); }
    };
  }

  #[@test]
  public function parse() {
    $syntax= new class() extends Syntax {
      public function rules() { return new Rules([new Returns('Test')]); }
    };
    $tokens= new class extends Tokens {
      public function next() { return null; }
      public function name($token) { return 'undefined'; }
    };
    $this->assertEquals('Test', $syntax->parse($tokens));
  }
}