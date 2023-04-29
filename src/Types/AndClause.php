<?php

namespace donatj\PhpDnfSolver\Types;

class AndClause implements DnfTypeInterface {

	/** @var LiteralDnfTypeInterface[] */
	private array $types;

	public function __construct( LiteralDnfTypeInterface...$types ) {
		$this->types = $types;
	}

	public function dnf() : string {
		if( count($this->types) === 1 ) {
			return $this->types[0]->dnf();
		}

		return implode('&', array_map(fn( DnfTypeInterface $type ) => $type->dnf(), $this->types));
	}

	public function matches( LiteralDnfTypeInterface|AndClause $value ) : bool {
		if( !$value instanceof AndClause ) {
			$value = new AndClause($value);
		}

		foreach( $this->types as $type ) {
			foreach( $value->types as $valueType ) {
				if( $type->matches($valueType) ) {
					continue 2;
				}
			}

			return false;
		}

		return true;
	}

	public function count() : int {
		return count($this->types);
	}

}
