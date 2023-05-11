<?php

namespace Tests\Types;

use donatj\PhpDnfSolver\Exceptions\InvalidArgumentException;
use donatj\PhpDnfSolver\Types\UserDefinedType;
use PHPUnit\Framework\TestCase;
use Stubs\Interfaces\BazAwareInterface;
use Stubs\Traits\BazAwareTrait;

class UserDefinedTypeTest extends TestCase {

	public function test__construct_exception() : void {
		$this->expectException(InvalidArgumentException::class);
		new UserDefinedType('Does\\Not\\Exist');
	}

	public function testDnf() : void {
		$this->assertSame(__CLASS__, (new UserDefinedType(self::class))->dnf());
		$this->assertSame(BazAwareTrait::class, (new UserDefinedType(BazAwareTrait::class))->dnf());
		$this->assertSame(BazAwareInterface::class, (new UserDefinedType(BazAwareInterface::class))->dnf());
	}

	public function test_getTypeName() : void {
		$this->assertSame(__CLASS__, (new UserDefinedType(self::class))->getTypeName());
		$this->assertSame(BazAwareTrait::class, (new UserDefinedType(BazAwareTrait::class))->getTypeName());
		$this->assertSame(BazAwareInterface::class, (new UserDefinedType(BazAwareInterface::class))->getTypeName());
	}

	public function test_count() : void {
		$this->assertSame(1, (new UserDefinedType(self::class))->count());
		$this->assertSame(1, (new UserDefinedType(BazAwareTrait::class))->count());
		$this->assertSame(1, (new UserDefinedType(BazAwareInterface::class))->count());
	}

}
