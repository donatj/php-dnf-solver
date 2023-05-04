<?php

namespace donatj\PhpDnfSolver;

interface LiteralDnfTypeInterface extends DnfTypeInterface {

	/**
	 * Returns the fully qualified type name of this literal
	 */
	public function getTypeName() : string;

	/**
	 * Always 1 for literal types
	 */
	public function count() : int;

}
