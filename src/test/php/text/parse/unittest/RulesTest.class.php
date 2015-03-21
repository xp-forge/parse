<?php namespace text\parse\unittest;

use text\parse\Rules;
use text\parse\Returns;
use lang\ElementNotFoundException;

class RulesTest extends \unittest\TestCase {

  #[@test]
  public function can_create() {
    new Rules(new Returns(null));
  }

  #[@test]
  public function can_create_with_more_named_rules() {
    new Rules(new Returns(null), ['param' => new Returns('param')]);
  }

  #[@test]
  public function get_rule_by_name() {
    $param= new Returns('param');
    $this->assertEquals($param, (new Rules(new Returns(null), ['param' => $param]))->named('param'));
  }

  #[@test, @expect(ElementNotFoundException::class)]
  public function getting_non_existant_rule_raises_exception() {
    (new Rules(new Returns(null), []))->named('non-existant');
  }
}