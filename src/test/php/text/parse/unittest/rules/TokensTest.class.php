<?php namespace text\parse\unittest\rules;

use text\parse\Tokenized;
use text\parse\rules\Tokens;

class TokensTest extends \unittest\TestCase {

  #[@test]
  public function dotted_package_name() {
    $this->assertEquals(
      ['text', '.', 'parse', '.', 'v2'],
      (new Tokens(T_STRING, '.'))->consume([], new Tokenized('text.parse.v2'), [])->backing()
    );
  }

  #[@test]
  public function php_absolute_typename() {
    $this->assertEquals(
      ['\\', 'unittest', '\\', 'TestCase'],
      (new Tokens(T_STRING, T_NS_SEPARATOR))->consume([], new Tokenized('\unittest\TestCase'), [])->backing()
    );
  }

  #[@test]
  public function php_relative_typename() {
    $this->assertEquals(
      ['unittest', '\\', 'TestCase'],
      (new Tokens(T_STRING, T_NS_SEPARATOR))->consume([], new Tokenized('unittest\TestCase'), [])->backing()
    );
  }
}