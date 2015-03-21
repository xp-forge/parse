<?php namespace text\parse\unittest;

use text\parse\Rules;
use text\parse\rules\Returns;
use lang\ElementNotFoundException;
use lang\IllegalArgumentException;

class RulesTest extends \unittest\TestCase {
  private $start;

  public function setUp() {
    $this->start= new Returns('start');
  }

  #[@test]
  public function can_create() {
    new Rules([$this->start]);
  }

  #[@test]
  public function can_create_with_more_named_rules() {
    new Rules([$this->start, 'param' => new Returns('param')]);
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function cannot_create_without_start_rule() {
    new Rules(['param' => new Returns('param')]);
  }

  #[@test]
  public function get_rule_by_name() {
    $param= new Returns('param');
    $this->assertEquals($param, (new Rules([$this->start, 'param' => $param]))->named('param'));
  }

  #[@test, @expect(ElementNotFoundException::class)]
  public function getting_non_existant_rule_raises_exception() {
    (new Rules([$this->start]))->named('non-existant');
  }
}