<?php namespace text\parse\rules;

use text\parse\Rule;
use text\parse\Values;

/**
 * Repeats a given rules 0..n times; until it no longer fully matches.
 * 
 * @test  xp://text.parse.unittest.RepeatedTest
 * @test  xp://text.parse.unittest.RepeatedAddTest
 * @test  xp://text.parse.unittest.RepeatedMapTest
 */
class Repeated extends Rule {
  private $rule, $delimiter, $collect;

  /**
   * Creates a new repeated rule
   *
   * @param  text.parse.Rule $rule
   * @param  text.parse.Rule $delimiter
   * @param  text.parse.rules.Collection|string $collect
   */
  public function __construct(Rule $rule, Rule $delimiter= null, $collect= null) {
    $this->rule= $rule;
    $this->delimiter= $delimiter;
    $this->collection= $collect ?: Collect::$IN_ARRAY;  // FIXME: Remove after refactoring!
    if (null === $collect) {
      $this->collect= Collect::$IN_ARRAY->code();
    } else if ($collect instanceof Collection) {
      $this->collect= $collect->code();
    } else {
      $this->collect= $collect;
    }
  }

  public function code() {
    $id= $this->id();

    $code= '$v'.$id.'= []; l'.$id.':';
    $code.= $this->rule->code();
    $code.= 'if ($errors) goto e'.$id.';';
    $code.= strtr($this->collect, ['$values' => '$v'.$id]);

    if ($this->delimiter) {
      $code.= $this->delimiter->code();
      $code.= 'if ($errors) { $errors= []; goto e'.$id.'; }';
    }

    $code.= 'goto l'.$id.'; e'.$id.': $result= $v'.$id.';';
    return $code;
  }

  /**
   * Consume
   *
   * @param  [:text.parse.Rule] $rules
   * @param  text.parse.Tokens $tokens
   * @param  var[] $values
   * @return text.parse.Consumed
   */
  public function consume($rules, $tokens, $values) {
    $continue= true;

    do {
      $result= $this->rule->consume($rules, $tokens, []);
      if ($continue= $result->matched()) {
        $this->collection->collect($values, $result->backing());
        if ($this->delimiter) {
          $continue= $this->delimiter->consume($rules, $tokens, [])->matched();
        }
      }
    } while ($continue);

    return new Values($values);
  }

  /**
   * Creates a string representation
   *
   * @return string
   */
  public function toString() {
    return $this->getClassName().'(->'.$this->rule->toString().')';
  }
}