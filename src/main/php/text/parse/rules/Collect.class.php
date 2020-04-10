<?php namespace text\parse\rules;

use lang\Enum;

/**
 * Collects values
 *
 * @see   xp://text.parse.rules.Repeated
 */
abstract class Collect extends Enum implements Collection {
  public static $AS_MAP, $IN_ARRAY;

  static function __static() {
    self::$AS_MAP= new class(0, 'AS_MAP') extends Collect {
      static function __static() { }
      public function collect(&$values, $value) { $values[key($value)]= current($value); }
    };
    self::$IN_ARRAY= new class(1, 'IN_ARRAY') extends Collect {
      static function __static() { }
      public function collect(&$values, $value) { $values[]= $value; }
    };
  }
}