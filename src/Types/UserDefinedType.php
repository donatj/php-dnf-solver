<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\Exceptions\InvalidArgumentException;
use donatj\PhpDnfSolver\SingularDnfTypeInterface;
use donatj\PhpDnfSolver\Traits\UnwrapTrait;

/**
 * Represents a "user defined type" - a class, interface, or trait, etc.
 */
class UserDefinedType implements SingularDnfTypeInterface {

	use UnwrapTrait;

	/**
	 * @param string $className The name of the class, interface, or trait to be satisfied
	 * @throws \donatj\PhpDnfSolver\Exceptions\InvalidArgumentException if the user defined type does not exist after triggering registered autoloaders
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
		if($value instanceof AndClause) {
			$types = $value->getTypes();
			foreach($types as $type) {
				if($this->isSatisfiedBy($type)) {
					return true;
				}
			}
		}

		$value = $this->unwrap($value);
		if( !$value ) {
			return false;
		}

		return is_a($value->getTypeName(), $this->className, true);
	}

	public function count() : int {
		return 1;
	}

}
