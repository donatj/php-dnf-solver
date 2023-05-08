<?php

namespace Tests\Types;

use donatj\PhpDnfSolver\Types\CallableType;
use PHPUnit\Framework\TestCase;

class CallableTypeTest extends TestCase {

	public function testDnf() : void {
		$this->assertSame('callable', (new CallableType)->dnf());
	}

	public function test_getTypeName() : void {
		$this->assertSame('callable', (new CallableType)->getTypeName());
	}

	public function test_count() : void {
		$this->assertSame(1, (new CallableType)->count());
	}

}
