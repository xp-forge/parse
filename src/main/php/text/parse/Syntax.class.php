<?php namespace text\parse;

/**
 * Base class for syntaxes. Subclasses implement the `rules()`
 * method.
 *
 * @test  xp://text.parse.unittest.SyntaxTest
 */
abstract class Syntax extends \lang\Object {
  private $rules;

  /**
   * Initializes rules
   */
  public function __construct() {
    $this->rules= cast($this->rules(), 'text.parse.Rules');
  }

  /**
   * Returns rules to use to parse this syntax.
   * 
   * @return text.parse.Rules
   */
  protected abstract function rules();

  /**
   * Parses input
   *
   * @param  text.parse.Tokens $input
   * @return var
   * @throws lang.FormatException
   */
  public function parse(Tokens $input) {
    return $this->rules->start()->evaluate($this->rules, $input);
  }
}