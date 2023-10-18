<?php

namespace donatj\PhpDnfSolver;

interface NestedDnfTypeInterface extends DnfTypeInterface {

	/**
	 * @return array<\donatj\PhpDnfSolver\NestedDnfTypeInterface|\donatj\PhpDnfSolver\SingularDnfTypeInterface>
	 */
	public function getTypes() : array;

}
