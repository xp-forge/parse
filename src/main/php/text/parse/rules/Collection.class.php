<?php namespace text\parse\rules;

interface Collection {

  /**
   * Collects values
   *
   * @param  var $values
   * @param  var $value
   */
  public function collect(&$values, $value);
}