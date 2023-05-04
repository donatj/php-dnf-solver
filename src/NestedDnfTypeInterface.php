<?php

namespace donatj\PhpDnfSolver;

interface NestedDnfTypeInterface extends DnfTypeInterface {

	/**
	 * @return DnfTypeInterface[]
	 */
	public function getTypes() : array;

}
