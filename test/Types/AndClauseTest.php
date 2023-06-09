<?php

namespace Tests\Types;

use donatj\PhpDnfSolver\Types\AndClause;
use donatj\PhpDnfSolver\Types\BuiltInType;
use donatj\PhpDnfSolver\Types\UserDefinedType;
use PHPUnit\Framework\TestCase;
use Stubs\Interfaces\BazAwareInterface;
use Stubs\Interfaces\FooAwareInterface;

class AndClauseTest extends TestCase {

	public function test_getTypes() : void {
		$types = [
			new UserDefinedType(BazAwareInterface::class),
			new UserDefinedType(FooAwareInterface::class),
		];

		$clause = new AndClause(...$types);

		$this->assertSame($types, $clause->getTypes());
	}

	public function test_dnf() : void {
		$clause = new AndClause(
			new BuiltInType('float'),
		);

		$this->assertSame('float', $clause->dnf());

		$clause = new AndClause(
			new UserDefinedType(BazAwareInterface::class),
			new UserDefinedType(FooAwareInterface::class),
		);

		$this->assertSame('Stubs\Interfaces\BazAwareInterface&Stubs\Interfaces\FooAwareInterface', $clause->dnf());
	}

}
