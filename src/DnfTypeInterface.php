<?php

namespace donatj\PhpDnfSolver;

interface DnfTypeInterface extends \Countable {

	public function dnf() : string;

	public function isSatisfiedBy( DnfTypeInterface $value ) : bool;

}
