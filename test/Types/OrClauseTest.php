<?php

namespace Tests\Types;

use donatj\PhpDnfSolver\Types\AndClause;
use donatj\PhpDnfSolver\Types\BuiltInType;
use donatj\PhpDnfSolver\Types\OrClause;
use donatj\PhpDnfSolver\Types\UserDefinedType;
use Interfaces\BazAwareInterface;
use Interfaces\FooAwareInterface;
use Objects\Person\BarPersonInterface;
use PHPUnit\Framework\TestCase;

class OrClauseTest extends TestCase {

	public function test_getTypes_basic() : void {
		$testTypes = [
			new UserDefinedType(BazAwareInterface::class),
			new UserDefinedType(FooAwareInterface::class),
		];

		$clause = new OrClause(...$testTypes);

		$types = $clause->getTypes();
		$i     = 0;
		foreach( $types as $type ) {
			$this->assertInstanceOf(AndClause::class, $type, 'Assert that type is wrapped in an AndClause');
			$this->assertSame([ $testTypes[$i++] ], $type->getTypes());
		}
	}

	public function test_getTypes_nested() : void {
		$testTypes = [
			new UserDefinedType(BazAwareInterface::class),
			new AndClause(
				new UserDefinedType(FooAwareInterface::class),
				new UserDefinedType(BarPersonInterface::class),
			),
		];

		$clause = new OrClause(...$testTypes);

		$types = $clause->getTypes();
		$i     = 0;
		foreach( $types as $type ) {
			$this->assertInstanceOf(AndClause::class, $type, 'Assert that type is an AndClause');
			if(  $testTypes[$i] instanceof AndClause ) {
				$this->assertSame($testTypes[$i], $type);
			} else {
				$this->assertSame([ $testTypes[$i] ], $type->getTypes());
			}

			$i++;
		}
	}

	public function test_dnf() : void {
		$clause = new OrClause(
			new BuiltInType('float'),
		);

		$this->assertSame('float', $clause->dnf());

		$clause = new OrClause(
			new UserDefinedType(BazAwareInterface::class),
			new UserDefinedType(FooAwareInterface::class),
		);

		$this->assertSame('Interfaces\BazAwareInterface|Interfaces\FooAwareInterface', $clause->dnf());
	}

}
