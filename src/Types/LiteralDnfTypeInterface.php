<?php

namespace donatj\PhpDnfSolver\Types;

interface LiteralDnfTypeInterface extends DnfTypeInterface {
	public function getTypeName() : string;

	public function matches( LiteralDnfTypeInterface $value ) : bool;

}
