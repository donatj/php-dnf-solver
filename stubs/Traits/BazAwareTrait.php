<?php

namespace Stubs\Traits;

trait BazAwareTrait {

	/** @var int[] */
	protected $bazIds;

	/** @var callable */
	protected $bazIdsCallable;

	/**
	 * @param int[] $bazIds
	 */
	public function setBazIds( array $bazIds ) : void {
		$this->bazIds = $bazIds;
	}

	public function setBazIdsCallable( callable $bazIdsCallable ) : void {
		$this->bazIdsCallable = $bazIdsCallable;
	}

	/**
	 * @return int[]
	 */
	public function getBazIds() : array {
		if( !isset($this->bazIds) && $this->bazIdsCallable instanceof \Closure ) {
			$this->bazIds = call_user_func($this->bazIdsCallable);
		}

		return $this->bazIds;
	}

}
