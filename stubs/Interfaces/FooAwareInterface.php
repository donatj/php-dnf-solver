<?php

namespace Interfaces;

interface FooAwareInterface {

	/**
	 * @param int[] $fooIds
	 */
	public function setFooIds( array $fooIds ) : void;

	/**
	 * @return int[]
	 */
	public function getFooIds() : array;

}
