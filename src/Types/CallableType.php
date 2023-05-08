<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\SingularDnfTypeInterface;
use donatj\PhpDnfSolver\Traits\UnwrapTrait;

/**
 * Represents a "callable" type
 *
 * This includes:
 * - callable
 * - Closure
 * - Invokable classes
 */
class CallableType implements SingularDnfTypeInterface {

	use UnwrapTrait;

	public function dnf() : string {
		return 'callable';
	}

	public function isSatisfiedBy( DnfTypeInterface $value ) : bool {
		$value = $this->unwrap($value);
		if( !$value ) {
			return false;
		}

		if( $value instanceof CallableType ) {
			return true;
		}

		if( $value instanceof UserDefinedType ) {
			return $this->isClassInvokable($value->getTypeName());
		}

		return false;
	}

	private function isClassInvokable( string $className ) : bool {
		return (new \ReflectionClass($className))->hasMethod('__invoke');
	}

	public function getTypeName() : string {
		return 'callable';
	}

	public function count() : int {
		return 1;
	}

}
