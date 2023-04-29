<?php

namespace donatj\PhpDnfSolver\Types;

interface DnfTypeInterface extends \Countable {

	public function dnf() : string;

}
