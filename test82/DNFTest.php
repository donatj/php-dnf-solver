<?php

namespace Tests82;


use donatj\PhpDnfSolver\DNF;
use donatj\PhpDnfSolver\Types\OrClause;
use Interfaces\BazAwareInterface;
use Interfaces\FooAwareInterface;
use PHPUnit\Framework\TestCase;

class DNFTest extends TestCase {

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

}
