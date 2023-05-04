<?php

namespace Tests;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\Types\AndClause;
use donatj\PhpDnfSolver\Types\BuiltInType;
use donatj\PhpDnfSolver\Types\OrClause;
use donatj\PhpDnfSolver\Types\UserDefinedType;
use Interfaces\BazAwareInterface;
use Interfaces\FooAwareInterface;
use Objects\Person\BarPersonInterface;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase {

	/**
	 * @dataProvider trueCaseProviders
	 */
	public function testDNF( DnfTypeInterface $paramType, DnfTypeInterface $returnType ) : void {
		$match = $paramType->isSatisfiedBy($returnType);

		$this->assertTrue($match, 'Assert that ' . $returnType->dnf() . ' fulfills ' . $paramType->dnf());
	}

	/**
	 * @dataProvider falseCaseProviders
	 */
	public function testDNFFalse( DnfTypeInterface $paramType, DnfTypeInterface $returnType ) : void {
		$match = $paramType->isSatisfiedBy($returnType);

		$this->assertFalse($match, 'Assert that ' . $returnType->dnf() . ' does not fulfill ' . $paramType->dnf());
	}

	public static function trueCaseProviders() : \Generator {
		yield [
			new BuiltInType('int'),
			new BuiltInType('int'),
		];

		yield [
			new BuiltInType('float'),
			new BuiltInType('int'),
		];

		yield [
			new BuiltInType('float'),
			new OrClause(new BuiltInType('float')),
		];

		yield [
			new UserDefinedType(BazAwareInterface::class),
			new OrClause(new UserDefinedType(BazAwareInterface::class)),
		];

		yield [
			new OrClause(
				new AndClause(
					new UserDefinedType(BazAwareInterface::class),
				),
			),

			new OrClause(
				new AndClause(
					new UserDefinedType(BazAwareInterface::class),
					new UserDefinedType(BarPersonInterface::class),

				),
				new AndClause(
					new UserDefinedType(BazAwareInterface::class),
					new UserDefinedType(FooAwareInterface::class),

				),
			),
		];

		yield [
			new OrClause(
				new AndClause(
					new UserDefinedType(BazAwareInterface::class),
				),
				new BuiltInType('null'),
			),

			new OrClause(
				new AndClause(
					new UserDefinedType(BazAwareInterface::class),
					new UserDefinedType(BarPersonInterface::class),
				),
				new AndClause(
					new UserDefinedType(BazAwareInterface::class),
					new UserDefinedType(FooAwareInterface::class),

				),
			),
		];
	}

	public static function falseCaseProviders() : \Generator {
		yield [
			new BuiltInType('int'),
			new BuiltInType('string'),
		];

		yield [
			new UserDefinedType(BarPersonInterface::class),
			new BuiltInType('float'),
		];

		yield [
			new BuiltInType('float'),
			new OrClause(new BuiltInType('float'), new BuiltInType('string')),
		];

		yield [
			new UserDefinedType(BazAwareInterface::class),
			new OrClause(new UserDefinedType(BazAwareInterface::class), new UserDefinedType(FooAwareInterface::class)),
		];

		yield [
			new OrClause(
				new UserDefinedType(BazAwareInterface::class),
				new UserDefinedType(BazAwareInterface::class)
			),
			new BuiltInType('float'),
		];

		yield [
			new BuiltInType('float'),
			new OrClause(
				new UserDefinedType(BazAwareInterface::class),
				new UserDefinedType(BazAwareInterface::class)
			),
		];

		yield [
			new AndClause(new UserDefinedType(BazAwareInterface::class), new UserDefinedType(FooAwareInterface::class)),
			new OrClause(
				new UserDefinedType(BazAwareInterface::class),
				new UserDefinedType(BazAwareInterface::class)
			),
		];

		yield [
			new AndClause(new UserDefinedType(BazAwareInterface::class), new UserDefinedType(FooAwareInterface::class)),
			new UserDefinedType(BazAwareInterface::class),
		];

		yield [
			new OrClause(
				new AndClause(
					new UserDefinedType(BazAwareInterface::class),
				),
			),

			new OrClause(
				new AndClause(
					new UserDefinedType(BazAwareInterface::class),
					new UserDefinedType(BarPersonInterface::class),
				),
				new AndClause(
					new UserDefinedType(FooAwareInterface::class),

				),
			),
		];
	}

}
