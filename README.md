# Complex numbers sample class

## Usage
```
<?php

use Mzavatsky\Complex\ComplexNumber;

$a = ComplexNumber::zero();         // 0
$b = ComplexNumber::re(15);         // 15
$c = ComplexNumber::im(-5);         // -5*i
$d = ComplexNumber::reIm(-2, 4);    // -2 + 4*i

// ($a + $b) * $c
// -------------- - $a
//       $d
$result = $a->add($b)->mul($c)->div($d)->sub($a);
```

## How to run tests
```
$ composer install
$ ./vendor/bin/phpunit
```
