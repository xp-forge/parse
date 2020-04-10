<?php namespace text\parse\rules;

use text\parse\{Unexpected, Values};

class RecursionOf extends \text\parse\Rule {
  private $tokens, $precedence, $terminal;

  public function __construct($definition, $terminal) {
    $precedence= sizeof($definition);
    foreach ($definition as $i => $tokens) {
      foreach ($tokens as $token => $handler) {
        $this->tokens[$token]= $handler;
        $this->precedence[$token]= $precedence - $i;
      }
    }
    $this->terminal= $terminal;
  }

  private function value($val) {
    if (is_array($val)) {
      return $this->reduce($val);
    } else {
      return $val;
    }
  }

  private function reduce($pair) {
    //echo 'PAIR => ', var_dump($pair);
    $case= $pair['case'];
    $result= $this->value(array_shift($pair['values']));
    while (sizeof($pair['values']) > 0) {
      $result= $this->tokens[$case]([$result, $case, $this->value(array_shift($pair['values']))]);
    }
    //var_dump($result);
    return $result;
  }

  /**
   * Consume
   *
   * @param  [:text.parse.Rule] $rules
   * @param  text.parse.Tokens $tokens
   * @param  var[] $values
   * @return text.parse.Consumed
   */
  public function consume($tokens, $stream, $values) {
    $begin= $stream->position();

    $precedence= -1;
    do {
      $terminal= $this->terminal->consume($tokens, $stream, []);
      if ($terminal->matched()) {
        $token= $stream->token();
        $case= null === $token ? null : $token[0];
        if (isset($this->tokens[$case])) {
          if (-1 === $precedence) {
            $pair= ['parent' => null, 'case' => $case, 'values' => [$terminal->backing()]];
          } else if ($this->precedence[$case] > $precedence) {
            $parent= &$pair;
            $pair= &$pair['values'][];
            $pair= ['parent' => $parent, 'case' => $case, 'values' => [$terminal->backing()]];
          } else if ($this->precedence[$case] < $precedence) {
            $pair['values'][]= $terminal->backing();
            while (null !== $pair['parent']) {
              $pair= &$pair['parent'];
            }
            $pair= ['parent' => $pair['parent'], 'case' => $case, 'values' => [$pair]];
          } else if ($case === $pair['case']) {
            $pair['values'][]= $terminal->backing();
          } else {
            $pair['values'][]= $terminal->backing();
            $pair= ['parent' => $pair['parent'], 'case' => $case, 'values' => [$pair]];
            while (null !== $pair['parent']) {
              $pair= &$pair['parent'];
            }
          }

          $precedence= $this->precedence[$case];
          $stream->forward();
        } else {
          $pair['values'][]= $terminal->backing();

          if (isset($pair['case'])) {
            while (null !== $pair['parent']) {
              $pair= &$pair['parent'];
            }
            // echo '<<< ', var_export($pair, true), "\n";
            return new Values($this->reduce($pair));
          } else {
            // echo '<<< ', var_export($pair['values'][0], true), "\n";
            return new Values($pair['values'][0]);
          }
        }
      } else {
        $case= null;
      }
    } while (null !== $case);

    $stream->backup($begin);
    $token= $stream->token();
    return new Unexpected(
      sprintf(
        'Unexpected %s, expecting one of %s %s',
        $stream->nameOf(is_array($token) ? $token[0] : $token),
        $this->terminal->toString(),
        implode(', ', array_map([$stream, 'nameOf'], array_keys($this->tokens)))
      ),
      $stream->line()
    );
  }
}