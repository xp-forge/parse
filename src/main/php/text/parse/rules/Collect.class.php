<?php namespace text\parse\rules;

/**
 * Collects values
 *
 * @see   xp://text.parse.rules.Repeated
 */
abstract class Collect extends \lang\Enum implements Collection {
  public static $AS_MAP, $IN_ARRAY;

  static function __static() {
    self::$AS_MAP= newinstance(__CLASS__, [0, 'AS_MAP'], '{
      static function __static() { }
      public function collect(&$values, $value) { $values[key($value)]= current($value); }
    }');
    self::$IN_ARRAY= newinstance(__CLASS__, [1, 'IN_ARRAY'], '{
      static function __static() { }
      public function collect(&$values, $value) { $values[]= $value; }
    }');
  }
}