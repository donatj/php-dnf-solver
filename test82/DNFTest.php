<?php

namespace Tests82;

use donatj\PhpDnfSolver\DNF;
use donatj\PhpDnfSolver\Types\OrClause;
use PHPUnit\Framework\TestCase;
use Stubs\Interfaces\BazAwareInterface;
use Stubs\Interfaces\FooAwareInterface;
use Stubs\Objects\Person\BarPerson;
use Stubs\Objects\Person\InvokablePersonFactory;
use Tests\TypeHelperTrait;

class DNFTest extends TestCase {

	use TypeHelperTrait;

	public function test_getFromReflectionType() : void {
		$dnf = DNF::getFromReflectionType(
			(new \ReflectionFunction(function() : (FooAwareInterface&BazAwareInterface)|int|null {
				return 1;
			}))->getReturnType()
		);
		$this->assertInstanceOf(OrClause::class, $dnf);
		$this->assertCount(3, $dnf);
		$this->assertSame('(Stubs\Interfaces\FooAwareInterface&Stubs\Interfaces\BazAwareInterface)|int|null', $dnf->dnf());

		$types = $dnf->getTypes();
		$this->assertSame('Stubs\Interfaces\FooAwareInterface&Stubs\Interfaces\BazAwareInterface', $types[0]->dnf());
		$this->assertSame('int', $types[1]->dnf());
		$this->assertSame('null', $types[2]->dnf());
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

	public static function trueSatisfactionProvider() : \Generator {
		yield [
			self::firstParamType(function( (FooAwareInterface&BazAwareInterface)|null $foo ) { }),
			self::returnType(fn() : BarPerson => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			self::firstParamType(function( (FooAwareInterface&BazAwareInterface)|int $foo ) { }),
			self::returnType(fn() : int => 10),
		];

		yield [
			self::firstParamType(function( (FooAwareInterface&InvokablePersonFactory)|callable $foo ) { }),
			self::returnType(fn() : callable => fn() => 10),
		];
	}

	public static function falseSatisfactionProvider() : \Generator {
		yield [
			self::firstParamType(function( (FooAwareInterface&BazAwareInterface)|null $foo ) { }),
			self::returnType(fn() : FooAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			self::firstParamType(function( (FooAwareInterface&BazAwareInterface)|null $foo ) { }),
			self::returnType(fn() : ?FooAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			self::firstParamType(function( callable $foo ) { }),
			self::returnType(fn() : (FooAwareInterface&InvokablePersonFactory)|callable => fn() => 10),
		];
	}

}
