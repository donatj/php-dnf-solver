<?php

namespace donatj\PhpDnfSolver\Types;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\LiteralDnfTypeInterface;
use donatj\PhpDnfSolver\NestedDnfTypeInterface;

/**
 * Represents a "and clause" - a set of types which must all be satisfied - e.g. "A&B&C"
 */
class AndClause implements NestedDnfTypeInterface {

	/** @var LiteralDnfTypeInterface[] */
	private array $types;

	/**
	 * @param \donatj\PhpDnfSolver\LiteralDnfTypeInterface ...$types The list of types to be satisfied
	 */
	public function __construct( LiteralDnfTypeInterface...$types ) {
		$this->types = $types;
	}

	public function dnf() : string {
		return implode('&', array_map(
			fn ( DnfTypeInterface $type ) => $type->dnf(), $this->types)
		);
	}

	public function isSatisfiedBy( DnfTypeInterface $value ) : bool {
		if( $value instanceof LiteralDnfTypeInterface ) {
			$value = new AndClause($value);
		}

		foreach( $this->getTypes() as $type ) {
			foreach( $value->getTypes() as $valueType ) {
				if( $type->isSatisfiedBy($valueType) ) {
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

	/**
	 * @return \donatj\PhpDnfSolver\LiteralDnfTypeInterface[]
	 */
	public function getTypes() : array {
		return $this->types;
	}

}
