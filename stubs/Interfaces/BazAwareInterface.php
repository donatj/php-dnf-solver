<?php

namespace Interfaces;

interface BazAwareInterface {

	/**
	 * @param int[] $bazIds
	 */
	public function setBazIds( array $bazIds ) : void;

	/**
	 * @return int[]
	 */
	public function getBazIds() : array;

}
