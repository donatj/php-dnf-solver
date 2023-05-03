<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\LiteralDnfTypeInterface;

/**
 * Represents a "or" clause - a set of types where any one of them must be satisfied - e.g. "A|B|(C&D)"
 */
class OrClause implements DnfTypeInterface {

	/** @var AndClause[] */
	private array $types;

	/**
	 * @param AndClause|LiteralDnfTypeInterface ...$types The list of types to be satisfied. Does not accept an OrClause as DNF defines that as invalid.
	 */
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

	public function isSatisfiedBy( DnfTypeInterface $value ) : bool {
		if( $value instanceof LiteralDnfTypeInterface ) {
			$value = new OrClause($value);
		}

		foreach( $value->types as $valueType ) {
			$matched = false;
			foreach( $this->types as $type ) {
				if( $type->isSatisfiedBy($valueType) ) {
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
