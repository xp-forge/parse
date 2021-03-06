Parse change log
================

## ?.?.? / ????-??-??

## 4.0.0 / 2020-10-04

* **Heads up:** Renamed `text.parse.rules.Match` class to *Matches* as
  match is a keyword in PHP 8: https://wiki.php.net/rfc/match_expression_v2
  (@thekid)

## 3.0.0 / 2020-04-10

* Implemented xp-framework/rfc#334: Drop PHP 5.6:
  . **Heads up:** Minimum required PHP version now is PHP 7.0.0
  . Rewrote code base, grouping use statements
  . Converted `newinstance` to anonymous classes
  (@thekid)

## 2.0.2 / 2020-01-07

* Added compatibility with XP 10 - @thekid

## 2.0.1 / 2019-08-26

* Added PHP 7.2, PHP 7.3 and PHP 7.4 to test matrix - @thekid
* Removed *Trying to access array offset on value of type null* warnings
  (@thekid)
* Changed code to use `util.Objects::stringOf()` instead of the deprecated
  `xp::stringOf()` core functionality.
  (@thekid)
* Changed code to use PHP 5.6 varargs instead of `func_get_args()`
  (@thekid)

## 2.0.0 / 2017-06-04

* **Heads up:** Dropped PHP 5.5 support - @thekid
* Added forward compatibility with XP 9.0.0 - @thekid

## 1.1.0 / 2016-08-28

* Added forward compatibility with XP 8.0.0 - @thekid

## 1.0.0 / 2016-02-21

* Added version compatibility with XP 7 - @thekid
* **Heads up**: Changed minimum XP version to XP 6.5.0, and with it the
  minimum PHP version to PHP 5.5.
  (@thekid)

## 0.4.0 / 2016-01-23

* Fix code to use `nameof()` instead of the deprecated `getClassName()`
  method from lang.Generic. See xp-framework/core#120
  (@thekid)

## 0.3.0 / 2015-07-12

* Added forward compatibility with XP 6.4.0 - @thekid
* Added preliminary PHP 7 support (alpha2, beta1) - @thekid

## 0.2.0 / 2015-04-12

* Added `text.parse.rules.Collection` interface. See the commit
  https://github.com/xp-forge/mirrors/commit/2e42289 for an example
  how this makes it easier to create collectors.
  (@thekid)

## 0.1.0 / 2015-04-12

* First public release - @thekid
