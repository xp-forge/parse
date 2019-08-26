Parse change log
================

## ?.?.? / ????-??-??

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
