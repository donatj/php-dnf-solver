<?php

namespace Tests;

use donatj\PhpDnfSolver\DNF;
use donatj\PhpDnfSolver\Types\AndClause;
use donatj\PhpDnfSolver\Types\BuiltInType;
use donatj\PhpDnfSolver\Types\OrClause;
use donatj\PhpDnfSolver\Types\UserDefinedType;
use PHPUnit\Framework\TestCase;
use Stubs\Interfaces\BazAwareInterface;
use Stubs\Interfaces\FooAwareInterface;
use Stubs\Objects\Person\BarPerson;
use Stubs\Objects\Person\BarPersonInterface;
use Stubs\Objects\Person\InvokablePersonFactory;
use Stubs\Objects\Person\PersonInterface;

class DNFTest extends TestCase {

	use TypeHelperTrait;

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
		$this->assertSame('Stubs\Interfaces\BazAwareInterface|null', $dnf->dnf());
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
		$this->assertSame('Stubs\Interfaces\FooAwareInterface|Stubs\Interfaces\BazAwareInterface', $dnf->dnf());

		$dnf = DNF::getFromReflectionType(
			(new \ReflectionFunction(function () : FooAwareInterface&BazAwareInterface {
				return $this->getMockBuilder(BarPersonInterface::class)->getMock();
			}))->getReturnType()
		);
		$this->assertInstanceOf(AndClause::class, $dnf);
		$this->assertSame('Stubs\Interfaces\FooAwareInterface&Stubs\Interfaces\BazAwareInterface', $dnf->dnf());
	}

	public function test_getFromReflectionType_exception() : void {
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessageMatches('/^Unknown ReflectionType: /');
		$test = new class extends \ReflectionType {

		};
		DNF::getFromReflectionType($test);
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
			self::firstParamType(function ( FooAwareInterface $foo ) { }),
			self::returnType(function () : FooAwareInterface { }),
		];

		yield [
			self::firstParamType(function ( float $foo ) { }),
			self::returnType(fn () : float => 8.0),
		];

		yield [
			self::firstParamType(function ( mixed $foo ) { }),
			self::returnType(fn () : float => 8.0),
		];

		yield [
			self::firstParamType(function ( mixed $foo ) { }),
			self::returnType(fn () : FooAwareInterface|BazAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			self::firstParamType(function ( FooAwareInterface $foo ) { }),
			self::returnType(fn () : FooAwareInterface&BazAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			self::firstParamType(function ( FooAwareInterface&BazAwareInterface $foo ) { }),
			self::returnType(fn () : BarPerson => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			self::firstParamType(function ( callable $foo ) { }),
			self::returnType(fn () : InvokablePersonFactory => $this->getMockBuilder(InvokablePersonFactory::class)->getMock()),
		];

		yield [
			self::firstParamType(function ( callable $foo ) { }),
			self::returnType(fn () : callable => $this->getMockBuilder(InvokablePersonFactory::class)->getMock()),
		];
	}

	public static function falseSatisfactionProvider() : \Generator {
		yield [
			self::firstParamType(function ( PersonInterface $foo ) { }),
			self::returnType(fn () : FooAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			self::firstParamType(function ( int $foo ) { }),
			self::returnType(fn () : float => 8.0),
		];

		yield [
			self::firstParamType(function ( float $foo ) { }),
			self::returnType(fn () : mixed => 8.0),
		];

		yield [
			self::firstParamType(function ( FooAwareInterface|BazAwareInterface $foo ) { }),
			self::returnType(fn () : mixed => 8.0),
		];

		yield [
			self::firstParamType(function ( FooAwareInterface&BazAwareInterface $foo ) { }),
			self::returnType(fn () : FooAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock()),
		];

		yield [
			self::firstParamType(function ( InvokablePersonFactory $foo ) { }),
			self::returnType(fn () : callable => $this->getMockBuilder(InvokablePersonFactory::class)->getMock()),
		];

		yield [
			self::firstParamType(function ( callable $foo ) { }),
			self::returnType(fn () : callable|InvokablePersonFactory => $this->getMockBuilder(InvokablePersonFactory::class)->getMock()),
		];

		yield [
			self::firstParamType(function ( callable $foo ) { }),
			self::returnType(fn () : string => "soup"),
		];
	}

	public function test_getFromVarType() : void {
		$foo = fn ( FooAwareInterface $foo ) => $foo;
		$rf  = new \ReflectionFunction($foo);
		$dnf = DNF::getFromVarType($rf->getParameters()[0]);
		$this->assertSame(FooAwareInterface::class, $dnf->dnf());

		$test = new class {

			public FooAwareInterface $bar;

		};
		$rf   = new \ReflectionProperty($test, 'bar');
		$dnf  = DNF::getFromVarType($rf);
		$this->assertSame(FooAwareInterface::class, $dnf->dnf());
	}

	public function test_getFromReturnType() : void {
		$foo = fn () : FooAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock();
		$rf  = new \ReflectionFunction($foo);
		$dnf = DNF::getFromReturnType($rf);
		$this->assertSame(FooAwareInterface::class, $dnf->dnf());

		$foo = fn () : FooAwareInterface|BazAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock();
		$rf  = new \ReflectionFunction($foo);
		$dnf = DNF::getFromReturnType($rf);
		$this->assertSame('Stubs\Interfaces\FooAwareInterface|Stubs\Interfaces\BazAwareInterface', $dnf->dnf());

		$foo = fn () : FooAwareInterface&BazAwareInterface => $this->getMockBuilder(FooAwareInterface::class)->getMock();
		$rf  = new \ReflectionFunction($foo);
		$dnf = DNF::getFromReturnType($rf);
		$this->assertSame('Stubs\Interfaces\FooAwareInterface&Stubs\Interfaces\BazAwareInterface', $dnf->dnf());
	}

}
