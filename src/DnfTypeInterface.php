<?php

namespace donatj\PhpDnfSolver;

use Countable;

interface DnfTypeInterface extends Countable {

	public function dnf() : string;

	public function isSatisfiedBy( DnfTypeInterface $value ) : bool;

}
