<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\Exceptions\InvalidArgumentException;
use donatj\PhpDnfSolver\LiteralDnfTypeInterface;

/**
 * Represents a "user defined type" - a class, interface, or trait, etc.
 */
class UserDefinedType implements LiteralDnfTypeInterface {

	/**
	 * @param string $className The name of the class, interface, or trait to be satisfied
	 * @throws \donatj\PhpDnfSolver\Exceptions\InvalidArgumentException if the class does not exist after triggering the register autoloaders
	 */
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
