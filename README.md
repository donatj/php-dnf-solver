# PHP DNF Solver

[![Latest Stable Version](https://poser.pugx.org/donatj/php-dnf-solver/version)](https://packagist.org/packages/donatj/php-dnf-solver)
[![License](https://poser.pugx.org/donatj/php-dnf-solver/license)](https://packagist.org/packages/donatj/php-dnf-solver)
[![ci.yml](https://github.com/donatj/php-dnf-solver/actions/workflows/ci.yml/badge.svg?)](https://github.com/donatj/php-dnf-solver/actions/workflows/ci.yml)


PHP DNF (Disjunctive Normal Form) Signature Compatibility Solver - see: https://wiki.php.net/rfc/dnf_types

## Requirements

- **php**: ^8.1

## Installing

Install the latest version with:

```bash
composer require 'donatj/php-dnf-solver'
```

## Example

```php
<?php

use donatj\PhpDnfSolver\DNF;
use donatj\PhpDnfSolver\Types\UserDefinedType;

require __DIR__ . '/../vendor/autoload.php';

interface A {}
interface B {}
interface C {}
interface D {}

class Foo implements A, B {}
class Bar implements B, C {}
class Baz implements C, D {}

function qux( A|(B&C) $aOrB ) : void {}

$quxParamType = (new ReflectionFunction('qux'))->getParameters()[0]->getType();

$quxDnf = DNF::getFromReflectionType($quxParamType);

var_dump($quxDnf->isSatisfiedBy(
	new UserDefinedType(Foo::class)
)); // true

var_dump($quxDnf->isSatisfiedBy(
	new UserDefinedType(Bar::class)
)); // true

var_dump($quxDnf->isSatisfiedBy(
	new UserDefinedType(Baz::class)
)); // false

```

Outputs:

```
bool(true)
bool(true)
bool(false)
```