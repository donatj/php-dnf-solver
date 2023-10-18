<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\NestedDnfTypeInterface;
use donatj\PhpDnfSolver\SingularDnfTypeInterface;
use donatj\PhpDnfSolver\Traits\UnwrapTrait;

/**
 * Represents a "built in type" as defined by ReflectionNamedType::isBuiltin()
 *
 * This includes:
 * - int
 * - float
 * - string
 * - bool
 * - array
 * - iterable
 *
 * @see https://www.php.net/manual/en/reflectionnamedtype.isbuiltin.php
 */
class BuiltInType implements SingularDnfTypeInterface {

	use UnwrapTrait;

	/**
	 * @param string $name The name of the built-in type
	 */
	public function __construct( private readonly string $name ) {
	}

	public function dnf() : string {
		return $this->name;
	}

	public function getTypeName() : string {
		return $this->name;
	}

	public function isSatisfiedBy( SingularDnfTypeInterface|NestedDnfTypeInterface $value ) : bool {
		if($this->name === 'mixed') {
			return true;
		}

		$value = $this->unwrap($value);
		if( !$value ) {
			return false;
		}

		$them = strtolower($value->getTypeName());
		$me   = strtolower($this->name);
		if( $them === $me ) {
			return true;
		}

		return $them === 'int' && ($me === 'float' || $me === 'string');
	}

	public function count() : int {
		return 1;
	}

}
