<?php

namespace donatj\PhpDnfSolver\Types;

class OrClause implements DnfTypeInterface {

	/** @var AndClause[] */
	private array $types;

	public function __construct( AndClause|LiteralDnfTypeInterface...$types ) {
		foreach( $types as $type ) {
			if( $type instanceof AndClause ) {
				$this->types[] = $type;
			} else {
				$this->types[] = new AndClause($type);
			}
		}
	}

	public function dnf() : string {
		return implode('|', array_map(function( DnfTypeInterface $type ) {
			return $type->count() > 1 ? "({$type->dnf()})" : $type->dnf();
		}, $this->types));
	}

	public function matches( LiteralDnfTypeInterface|AndClause|OrClause $value ) : bool {
		if( !$value instanceof OrClause ) {
			$value = new OrClause($value);
		}

		foreach( $value->types as $valueType ) {
			$matched = false;
			foreach( $this->types as $type ) {
				if( $type->matches($valueType) ) {
					$matched = true;
				}
			}

			if( !$matched ) {
				return false;
			}
		}

		return true;
	}

	public function count() : int {
		return count($this->types);
	}

}
