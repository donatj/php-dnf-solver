<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\LiteralDnfTypeInterface;

class BuiltInType implements LiteralDnfTypeInterface {

	public function __construct( private readonly string $name ) {
	}

	public function dnf() : string {
		return $this->name;
	}

	public function getTypeName() : string {
		return $this->name;
	}

	public function matches( DnfTypeInterface $value ) : bool {
		if( !$value instanceof LiteralDnfTypeInterface ) {
			return false;
		}

		$them = strtolower($value->getTypeName());
		$me   = strtolower($this->name);
		if( $them === $me ) {
			return true;
		}

		if( $them === 'int' && ($me === 'float' || $me === 'string') ) {
			return true;
		}

		return false;
	}

	public function count() : int {
		return 1;
	}
}
