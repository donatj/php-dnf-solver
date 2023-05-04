<?php

namespace donatj\PhpDnfSolver\Traits;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\LiteralDnfTypeInterface;
use donatj\PhpDnfSolver\NestedDnfTypeInterface;

trait UnnestTrait {

	/**
	 * Un-nests a singular literal DNF type to a literal, or null if it cannot be unnested
	 */
	private function unnest(DnfTypeInterface $value) : ?LiteralDnfTypeInterface {
		if ( $value instanceof LiteralDnfTypeInterface ) {
			return $value;
		}

		for($i = 0; $i < 4; $i++) {
			if( $value instanceof NestedDnfTypeInterface && count($value) === 1 ) {
				$value = $value->getTypes()[0];
			}else{
				break;
			}
		}

		return $value instanceof LiteralDnfTypeInterface ? $value : null;
	}

}
