<?php

namespace Tests82;

use donatj\PhpDnfSolver\DNF;
use donatj\PhpDnfSolver\Types\OrClause;
use Interfaces\BazAwareInterface;
use Interfaces\FooAwareInterface;
use Objects\Person\BarPerson;
use PHPUnit\Framework\TestCase;
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
		$this->assertSame('(Interfaces\FooAwareInterface&Interfaces\BazAwareInterface)|int|null', $dnf->dnf());

		$types = $dnf->getTypes();
		$this->assertSame('Interfaces\FooAwareInterface&Interfaces\BazAwareInterface', $types[0]->dnf());
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

	public function trueSatisfactionProvider() : \Generator {
		yield [
			$this->firstParamType(function( (FooAwareInterface&BazAwareInterface)|null $foo ) { }),
			$this->returnType(fn() : BarPerson => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			$this->firstParamType(function( (FooAwareInterface&BazAwareInterface)|int $foo ) { }),
			$this->returnType(fn() : int => 10),
		];
	}

	public function falseSatisfactionProvider() : \Generator {
		yield [
			$this->firstParamType(function( (FooAwareInterface&BazAwareInterface)|null $foo ) { }),
			$this->returnType(fn() : FooAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			$this->firstParamType(function( (FooAwareInterface&BazAwareInterface)|null $foo ) { }),
			$this->returnType(fn() : ?FooAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];
	}

}
