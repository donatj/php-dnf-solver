<?php

namespace Traits;

use Closure;

trait FooAwareTrait {

	/** @var int[] */
	protected array $adminIds;

	/** @var callable */
	protected $adminIdsCallable;

	/**
	 * @param int[] $fooIds
	 */
	public function setFooIds( array $fooIds ) : void {
		$this->adminIds = $fooIds;
	}

	public function setFooIdsCallable( callable $fooIdsCallable ) : void {
		$this->adminIdsCallable = $fooIdsCallable;
	}

	/**
	 * @return int[]
	 */
	public function getFooIds() : array {
		if( !isset($this->adminIds) && $this->adminIdsCallable instanceof \Closure ) {
			$this->adminIds = call_user_func($this->adminIdsCallable);
		}

		return $this->adminIds;
	}

}
