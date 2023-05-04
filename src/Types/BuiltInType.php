<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\LiteralDnfTypeInterface;
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
class BuiltInType implements LiteralDnfTypeInterface {

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

	public function isSatisfiedBy( DnfTypeInterface $value ) : bool {
		$value = $this->unwrap($value);
		if( !$value ) {
			return false;
		}

		if( !$value instanceof LiteralDnfTypeInterface ) {
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
