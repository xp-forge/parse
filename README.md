Parse
=====

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-forge/parse.svg)](http://travis-ci.org/xp-forge/parse)
[![XP Framework Mdodule](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_4plus.png)](http://php.net/)
[![Required PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Required HHVM 3.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_5plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/parse/version.png)](https://packagist.org/packages/xp-forge/parse)

Parses code based on rules.

Example
-------
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

Rules
-----
The following rules are available for matching:

### Token
The rule *Token(T)* matches a single token `T`.

### Tokens
The rule *Tokens(T1[, T2[, ...]])* matches any combination of the given tokens. For example, `new Tokens(T_STRING, '.')` can be used to match dotted type notation as used in XP's type names.

Be aware of the fact that this will match three dots, or three strings, or a string and a dot; and therefore does not guarantee syntactical correctness. It is, however, a high-performance alternative to more complex rules.

### Apply
The rule *Apply(RuleName)* will defer handling to a given named rule passed to the `Rules` constructor.

### Match
The rule *Match([T1 => Rule1[, T2 => Rule2[, ...]]])* matches rules based on the initial tokens used in the lookup map. High-performance due to `isset()`-based lookups, though less flexible as `OneOf`.

### OneOf
The rule *OneOf([Rule1[, Rule2[, ...]]])* matches rules in the order specified and returns the values of the first rule
to match.

### Sequence
The rule *Sequence([Rule1[, Rule2[, ...]]], function)* matches a sequence of rules in the order specified, and passed the matched values to the handler function.

### Optional
The rule *Optional(Rule, default= NULL)* matches the rule, and returns it value; or the default if not matched.

### Repeated
The rule *Repeated(Rule, Delim= NULL, collect= IN_ARRAY)* matches the rule (and optionally, a given delimiter rule) as many times as possible. It uses a collector function from the `text.parse.rules.Collect` enum to process the results.

An example is processing argument lists, e.g. `new Repeated(new Apply('val'), new Token(','))` will parse arguments to a function. Dangling delimiters are allowed.

