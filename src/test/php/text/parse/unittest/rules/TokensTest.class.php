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
    $this->assertEquals(
      ['\\', 'unittest', '\\', 'TestCase'],
      (new Tokens(T_STRING, T_NS_SEPARATOR))->consume([], new Tokenized('\unittest\TestCase'), [])->backing()
    );
  }

  #[Test]
  public function php_relative_typename() {
    $this->assertEquals(
      ['unittest', '\\', 'TestCase'],
      (new Tokens(T_STRING, T_NS_SEPARATOR))->consume([], new Tokenized('unittest\TestCase'), [])->backing()
    );
  }
}