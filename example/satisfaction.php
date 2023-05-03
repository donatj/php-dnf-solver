<?php

namespace Examples;

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

$qux = function ( A|(B&C) $aOrB ) : void {};

$quxParamType = (new \ReflectionFunction($qux))->getParameters()[0]->getType();

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
