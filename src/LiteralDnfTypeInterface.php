<?php

namespace donatj\PhpDnfSolver;

interface LiteralDnfTypeInterface extends DnfTypeInterface {

	public function getTypeName() : string;

}
