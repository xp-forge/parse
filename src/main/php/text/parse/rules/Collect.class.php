<?php namespace text\parse\rules;

/**
 * Collects values
 *
 * @see   xp://text.parse.rules.Repeated
 */
abstract class Collect extends \lang\Enum {
  public static $IN_MAP, $IN_ARRAY;

  static function __static() {
    self::$IN_MAP= newinstance(__CLASS__, [0, 'IN_MAP'], '{
      static function __static() { }
      public function collect(&$values, $value) { $values[key($value)]= current($value); }
    }');
    self::$IN_ARRAY= newinstance(__CLASS__, [1, 'IN_ARRAY'], '{
      static function __static() { }
      public function collect(&$values, $value) { $values[]= $value; }
    }');
  }

  /**
   * Collects values
   *
   * @param  var $values
   * @param  var $value
   */
  public abstract function collect(&$values, $value);
}