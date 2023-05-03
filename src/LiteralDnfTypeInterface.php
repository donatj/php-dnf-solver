<?php

namespace donatj\PhpDnfSolver;

interface LiteralDnfTypeInterface extends DnfTypeInterface {

	/**
	 * Returns the fully qualified type name of this literal
	 */
	public function getTypeName() : string;

	/**
	 * Returns the number of types in this DNF type - always 1 for a literal
	 */
	public function count() : int;

}
