<?php

namespace donatj\PhpDnfSolver;

interface DnfTypeInterface extends \Countable {

	/**
	 * Return the canonical string representation of the DNF representation of this type
	 */
	public function dnf() : string;

	/**
	 * Tests if this type is satisfied by the given type
	 *
	 * For example, if this type is "A|(B&C)" and the given type matches just "A", this method returns true.
	 * If the given type matches just "B", this method returns false.
	 * If the given type matches "B&C", this method returns true.
	 */
	public function isSatisfiedBy( DnfTypeInterface $value ) : bool;

	/**
	 * Returns the number of types in this DNF type
	 */
	public function count() : int;

}
