<?php

namespace donatj\PhpDnfSolver\Traits;

use donatj\PhpDnfSolver\DnfTypeInterface;
use donatj\PhpDnfSolver\NestedDnfTypeInterface;
use donatj\PhpDnfSolver\SingularDnfTypeInterface;

trait UnwrapTrait {

	/**
	 * Un-nests a singular yet nested DNF type to a singular, or null if it cannot be unwrapped
	 */
	private function unwrap(DnfTypeInterface $value) : ?SingularDnfTypeInterface {
		if ( $value instanceof SingularDnfTypeInterface ) {
			return $value;
		}

		for($i = 0; $i < 4; $i++) {
			if( $value instanceof NestedDnfTypeInterface && count($value) === 1 ) {
				$value = $value->getTypes()[0];
			}else{
				break;
			}
		}

		return $value instanceof SingularDnfTypeInterface ? $value : null;
	}

}
