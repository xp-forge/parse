<?php namespace text\parse\unittest\rules;

use text\parse\Tokenized;
use text\parse\rules\Tokens;
use unittest\Test;

class TokensTest extends \unittest\TestCase {

  #[Test]
  public function dotted_package_name() {
    $this->assertEquals(
      ['text', '.', 'parse', '.', 'v2'],
      (new Tokens(T_STRING, '.'))->consume([], new Tokenized('text.parse.v2'), [])->backing()
    );
  }

  #[Test]
  public function php_absolute_typename() {
    $tokens= new Tokens(T_STRING, T_NS_SEPARATOR, T_NAME_FULLY_QUALIFIED, T_NAME_QUALIFIED);
    $this->assertEquals(
      '\\unittest\\TestCase',
      implode('', $tokens->consume([], new Tokenized('\unittest\TestCase'), [])->backing())
    );
  }

  #[Test]
  public function php_relative_typename() {
    $tokens= new Tokens(T_STRING, T_NS_SEPARATOR, T_NAME_FULLY_QUALIFIED, T_NAME_QUALIFIED);
    $this->assertEquals(
      'unittest\\TestCase',
      implode('', $tokens->consume([], new Tokenized('unittest\TestCase'), [])->backing())
    );
  }
}