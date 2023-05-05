<?php

namespace Tests;

use donatj\PhpDnfSolver\DNF;
use donatj\PhpDnfSolver\Types\AndClause;
use donatj\PhpDnfSolver\Types\BuiltInType;
use donatj\PhpDnfSolver\Types\OrClause;
use donatj\PhpDnfSolver\Types\UserDefinedType;
use Interfaces\BazAwareInterface;
use Interfaces\FooAwareInterface;
use Objects\Person\BarPersonInterface;
use Objects\Person\PersonInterface;
use PHPUnit\Framework\TestCase;

class DNFTest extends TestCase {

	public function test_getFromReflectionType() : void {
		$dnf = DNF::getFromReflectionType(
			(new \ReflectionFunction(function () : int {
				return 1;
			}))->getReturnType()
		);
		$this->assertInstanceOf(BuiltInType::class, $dnf);
		$this->assertSame('int', $dnf->dnf());

		$dnf = DNF::getFromReflectionType(
			(new \ReflectionFunction(function () : ?int {
				return 1;
			}))->getReturnType()
		);
		$this->assertSame('int|null', $dnf->dnf());
		$this->assertInstanceOf(OrClause::class, $dnf);
		$dnfTypes = $dnf->getTypes();
		$this->assertInstanceOf(BuiltInType::class, $dnfTypes[0]->getTypes()[0]);
		$this->assertSame('int', $dnfTypes[0]->dnf());
		$this->assertInstanceOf(BuiltInType::class, $dnfTypes[1]->getTypes()[0]);
		$this->assertSame('null', $dnfTypes[1]->dnf());

		$dnf = DNF::getFromReflectionType(
			(new \ReflectionFunction(function () : BazAwareInterface {
				return $this->getMockBuilder(BazAwareInterface::class)->getMock();
			}))->getReturnType()
		);
		$this->assertInstanceOf(UserDefinedType::class, $dnf);
		$this->assertSame(BazAwareInterface::class, $dnf->dnf());

		$dnf = DNF::getFromReflectionType(
			(new \ReflectionFunction(function () : ?BazAwareInterface {
				return $this->getMockBuilder(BazAwareInterface::class)->getMock();
			}))->getReturnType()
		);
		$this->assertSame('Interfaces\BazAwareInterface|null', $dnf->dnf());
		$this->assertInstanceOf(OrClause::class, $dnf);
		$dnfTypes = $dnf->getTypes();
		$this->assertInstanceOf(UserDefinedType::class, $dnfTypes[0]->getTypes()[0]); // Gets jammed into an AND
		$this->assertSame(BazAwareInterface::class, $dnfTypes[0]->getTypes()[0]->dnf());
		$this->assertInstanceOf(BuiltInType::class, $dnfTypes[1]->getTypes()[0]);
		$this->assertSame('null', $dnfTypes[1]->dnf());

		$dnf = DNF::getFromReflectionType(
			(new \ReflectionFunction(function () : FooAwareInterface|BazAwareInterface {
				return $this->getMockBuilder(BazAwareInterface::class)->getMock();
			}))->getReturnType()
		);
		$this->assertInstanceOf(OrClause::class, $dnf);
		$this->assertSame('Interfaces\FooAwareInterface|Interfaces\BazAwareInterface', $dnf->dnf());

		$dnf = DNF::getFromReflectionType(
			(new \ReflectionFunction(function () : FooAwareInterface&BazAwareInterface {
				return $this->getMockBuilder(BarPersonInterface::class)->getMock();
			}))->getReturnType()
		);
		$this->assertInstanceOf(AndClause::class, $dnf);
		$this->assertSame('Interfaces\FooAwareInterface&Interfaces\BazAwareInterface', $dnf->dnf());
	}

	private function firstParamType( callable $reflection ) : \ReflectionType {
		return (new \ReflectionFunction($reflection))->getParameters()[0]->getType();
	}

	private function returnType( callable $reflection ) : \ReflectionType {
		return (new \ReflectionFunction($reflection))->getReturnType();
	}

	/**
	 * @dataProvider trueSatisfactionProvider
	 */
	public function test_reflectionTypeSatisfiesReflectionType_true(
		\ReflectionType $satisfyingType,
		\ReflectionType $satisfiedType
	) : void {
		$this->assertTrue(DNF::reflectionTypeSatisfiesReflectionType(
			$satisfyingType,
			$satisfiedType
		));
	}

	/**
	 * @dataProvider falseSatisfactionProvider
	 */
	public function test_reflectionTypeSatisfiesReflectionType_false(
		\ReflectionType $satisfyingType,
		\ReflectionType $satisfiedType
	) : void {
		$this->assertFalse(DNF::reflectionTypeSatisfiesReflectionType(
			$satisfyingType,
			$satisfiedType
		));
	}

	public function trueSatisfactionProvider() : \Generator {
		yield [
			$this->firstParamType(function ( FooAwareInterface $foo ) { }),
			$this->returnType(function () : FooAwareInterface { }),
		];

		yield [
			$this->firstParamType(function ( float $foo ) { }),
			$this->returnType(fn () : float => 8.0),
		];

		yield [
			$this->firstParamType(function ( mixed $foo ) { }),
			$this->returnType(fn () : float => 8.0),
		];

		yield [
			$this->firstParamType(function ( mixed $foo ) { }),
			$this->returnType(fn () : FooAwareInterface|BazAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];
	}

	public function falseSatisfactionProvider() : \Generator {
		yield [
			$this->firstParamType(function ( PersonInterface $foo ) { }),
			$this->returnType(fn () : FooAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			$this->firstParamType(function ( int $foo ) { }),
			$this->returnType(fn () : float => 8.0),
		];

		yield [
			$this->firstParamType(function ( float $foo ) { }),
			$this->returnType(fn () : mixed => 8.0),
		];

		yield [
			$this->firstParamType(function ( FooAwareInterface|BazAwareInterface $foo ) { }),
			$this->returnType(fn () : mixed => 8.0),
		];
	}

}
