<?php namespace text\parse\unittest;

use text\parse\Tokens;
use unittest\{Test, TestCase};

class TokensTest extends TestCase {

  /**
   * Returns new tokens
   *
   * @param  var[] $input
   * @return text.parse.Tokens
   */
  private function newFixture($input) {
    return new class($input) extends Tokens {
      public $input= [];
      public function __construct($input) { $this->input= $input; }
      public function next() { return array_shift($this->input); }
      public function name($token) { return '#'.$token; }
    };
  }

  #[Test]
  public function empty_token_list() {
    $this->assertNull($this->newFixture([])->token());
  }

  #[Test]
  public function first_token() {
    $this->assertEquals('a', $this->newFixture(['a', 'b', 'c'])->token());
  }

  #[Test]
  public function position_initially_zero() {
    $this->assertEquals(0, $this->newFixture(['a', 'b', 'c'])->position());
  }

  #[Test]
  public function calling_token_method_does_not_forward() {
    $fixture= $this->newFixture(['a', 'b', 'c']);
    $this->assertEquals(['a', 'a', 'a'], [$fixture->token(), $fixture->token(), $fixture->token()]);
  }

  #[Test]
  public function second_token_returned_after_forward() {
    $fixture= $this->newFixture(['a', 'b', 'c']);
    $fixture->token();
    $fixture->forward();

    $this->assertEquals('b', $fixture->token());
  }

  #[Test]
  public function position_after_forward() {
    $fixture= $this->newFixture(['a', 'b', 'c']);
    $fixture->token();
    $fixture->forward();

    $this->assertEquals(1, $fixture->position());
  }

  #[Test]
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