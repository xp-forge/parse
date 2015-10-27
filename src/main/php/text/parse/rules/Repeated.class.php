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
  private $rule, $delimiter, $collection;

  /**
   * Creates a new repeated rule
   *
   * @param  text.parse.Rule $rule
   * @param  text.parse.Rule $delimiter
   * @param  text.parse.rules.Collection $collect
   */
  public function __construct(Rule $rule, Rule $delimiter= null, Collection $collect= null) {
    $this->rule= $rule;
    $this->delimiter= $delimiter;
    $this->collection= $collect ?: Collect::$IN_ARRAY;
  }

  public function code() {
    $code= "\n".'  echo "LOOPING";'; // loop_'.strtr($this->hashCode(), '.', '_').':';
    $code.= $this->rule->code();
    /*
    $code.= '
       if ($result->matched()) {
         echo "COLLECT "; var_dump($result);
         goto loop_'.strtr($this->hashCode(), '.', '_').';
       }

       $result= new \text\parse\Values($values);
    ';
    */
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