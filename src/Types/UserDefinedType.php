<?php

namespace donatj\PhpDnfSolver\Types;

class UserDefinedType implements LiteralDnfTypeInterface {

	public function __construct( public string $className ) {
		if( !class_exists($className) && !interface_exists($className) && !trait_exists($className) ) {
			throw new \InvalidArgumentException("'{$className}' does not exist");
		}
	}

	public function dnf() : string {
		return $this->className;
	}

	public function getTypeName() : string {
		return $this->className;
	}

	public function matches( LiteralDnfTypeInterface $value ) : bool {
		return is_a($value->getTypeName(), $this->className, true);
	}

	public function count() : int {
		return 1;
	}
}
