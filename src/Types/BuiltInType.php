<?php

namespace donatj\PhpDnfSolver\Types;

class BuiltInType implements LiteralDnfTypeInterface {

	public function __construct( private readonly string $name ) {
	}

	public function dnf() : string {
		return $this->name;
	}

	public function getTypeName() : string {
		return $this->name;
	}

	public function matches( LiteralDnfTypeInterface $value ) : bool {
		$them = strtolower($value->getTypeName());
		$me   = strtolower($this->name);
		if($them === $me) {
			return true;
		}

		if($them === 'int' && ($me === 'float' || $me === 'string')) {
			return true;
		}

		return false;
	}

	public function count() : int {
		return 1;
	}
}
