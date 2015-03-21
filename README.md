Parse
=====

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/parse.svg)](http://travis-ci.org/xp-forge/parse)
[![XP Framework Mdodule](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_4plus.png)](http://php.net/)
[![Required HHVM 3.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_5plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/parse/version.png)](https://packagist.org/packages/xp-forge/parse)

Parses code based on rules.

Examples
--------
The following example parses key/value pairs using the tokenizer built on top of PHP's tokenizer extension.

```php
use text\parse\Rules;
use text\parse\Tokenized;
use text\parse\rules\Repeated;
use text\parse\rules\Sequence;
use text\parse\rules\Token;
use text\parse\rules\Apply;
use text\parse\rules\Match;
use text\parse\rules\Collect;

$syntax= newinstance('text.parse.Syntax', [], [
  'rules' => function() { return new Rules([
    new Repeated(
      new Sequence([new Token(T_STRING), new Token(':'), new Apply('val')], function($values) {
        return [$values[0] => $values[2]];
      }),
      new Token(','),
      Collect::$AS_MAP
    ),
    'val' => new Match([
      T_CONSTANT_ENCAPSED_STRING => function($values) { return substr($values[0], 1, -1); },
      T_STRING                   => function($values) { return constant($values[0]); },
      T_DNUMBER                  => function($values) { return (double)$values[0]; },
      T_LNUMBER                  => function($values) { return (int)$values[0]; }
    ])
  ]); }
]);

$tokens= new Tokenized('a: 1, b: 2.0, c: true, d: "D"');
$pairs= $syntax->parse($tokens);  // ["a" => 1, "b" => 2.0, "c" => true, "d" => "D"]
```