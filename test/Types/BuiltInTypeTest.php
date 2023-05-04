<?php

namespace Tests\Types;

use donatj\PhpDnfSolver\Types\BuiltInType;
use PHPUnit\Framework\TestCase;

class BuiltInTypeTest extends TestCase {

	public function testDnf() : void {
		$this->assertSame('int', (new BuiltInType('int'))->dnf());
		$this->assertSame('float', (new BuiltInType('float'))->dnf());
		$this->assertSame('string', (new BuiltInType('string'))->dnf());
	}

	public function test_getTypeName() : void {
		$this->assertSame('int', (new BuiltInType('int'))->getTypeName());
		$this->assertSame('float', (new BuiltInType('float'))->getTypeName());
		$this->assertSame('string', (new BuiltInType('string'))->getTypeName());
	}

	public function test_count() : void {
		$this->assertSame(1, (new BuiltInType('int'))->count());
		$this->assertSame(1, (new BuiltInType('float'))->count());
		$this->assertSame(1, (new BuiltInType('string'))->count());
	}

}
