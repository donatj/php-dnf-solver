<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\Exceptions\InvalidArgumentException;
use donatj\PhpDnfSolver\LiteralDnfTypeInterface;

class UserDefinedType implements LiteralDnfTypeInterface {

	public function __construct( public string $className ) {
		if( !class_exists($className) && !interface_exists($className) && !trait_exists($className) ) {
			throw new InvalidArgumentException("'{$className}' does not exist");
		}
	}

	public function dnf() : string {
		return $this->className;
	}

	public function getTypeName() : string {
		return $this->className;
	}

	public function isSatisfiedBy( DnfTypeInterface $value ) : bool {
		if( !$value instanceof LiteralDnfTypeInterface ) {
			return false;
		}

		return is_a($value->getTypeName(), $this->className, true);
	}

	public function count() : int {
		return 1;
	}
}
