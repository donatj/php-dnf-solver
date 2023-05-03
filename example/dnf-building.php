<?php

namespace Examples;

use donatj\PhpDnfSolver\Types\AndClause;
use donatj\PhpDnfSolver\Types\BuiltInType;
use donatj\PhpDnfSolver\Types\OrClause;
use donatj\PhpDnfSolver\Types\UserDefinedType;

require __DIR__ . '/../vendor/autoload.php';

interface A {}
interface B {}
interface C {}

var_dump((new OrClause(
	new UserDefinedType(A::class),
	new UserDefinedType(B::class),
	new UserDefinedType(C::class)
))->dnf()); // A|B|C

var_dump((new OrClause(
	new UserDefinedType(A::class),
	new AndClause(
		new UserDefinedType(B::class),
		new UserDefinedType(C::class)
	)
))->dnf()); // A|(B&C)

var_dump((new OrClause(
	new AndClause(new UserDefinedType(A::class), new UserDefinedType(B::class)),
	new AndClause(
		new UserDefinedType(B::class),
		new UserDefinedType(C::class)
	),
	new BuiltInType('null'),
))->dnf()); // (A&B)|(B&C)|null
