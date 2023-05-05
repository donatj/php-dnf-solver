<?php

namespace donatj\PhpDnfSolver;

interface SingularDnfTypeInterface extends DnfTypeInterface {

	/**
	 * Returns the fully qualified type name of this type
	 */
	public function getTypeName() : string;

	/**
	 * Always 1 for singular types
	 */
	public function count() : int;

}
