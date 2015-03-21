<?php namespace text\parse\unittest;

use text\parse\Tokens;

class TokensTest extends \unittest\TestCase {

  /**
   * Returns new tokens
   *
   * @param  var[] $input
   * @return text.parse.Tokens
   */
  private function newFixture($input) {
    return newinstance('text.parse.Tokens', [$input], [
      'input'       => [],
      '__construct' => function($input) { $this->input= $input; },
      'next'        => function() { return array_shift($this->input); },
      'name'        => function($token) { return '#'.$token; }
    ]);
  }

  #[@test]
  public function empty_token_list() {
    $this->assertNull($this->newFixture([])->token());
  }

  #[@test]
  public function first_token() {
    $this->assertEquals('a', $this->newFixture(['a', 'b', 'c'])->token());
  }

  #[@test]
  public function position_initially_zero() {
    $this->assertEquals(0, $this->newFixture(['a', 'b', 'c'])->position());
  }

  #[@test]
  public function calling_token_method_does_not_forward() {
    $fixture= $this->newFixture(['a', 'b', 'c']);
    $this->assertEquals(['a', 'a', 'a'], [$fixture->token(), $fixture->token(), $fixture->token()]);
  }

  #[@test]
  public function second_token_returned_after_forward() {
    $fixture= $this->newFixture(['a', 'b', 'c']);
    $fixture->token();
    $fixture->forward();

    $this->assertEquals('b', $fixture->token());
  }

  #[@test]
  public function position_after_forward() {
    $fixture= $this->newFixture(['a', 'b', 'c']);
    $fixture->token();
    $fixture->forward();

    $this->assertEquals(1, $fixture->position());
  }

  #[@test]
  public function first_token_returned_after_backing_up() {
    $fixture= $this->newFixture(['a', 'b', 'c']);
    with ($position= $fixture->position()); {
      $fixture->token();
      $fixture->forward();
      $fixture->backup($position);
    }

    $this->assertEquals('a', $fixture->token());
  }
}