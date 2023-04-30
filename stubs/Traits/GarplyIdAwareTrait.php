<?php

namespace Traits;

trait GarplyIdAwareTrait {

	protected int $garplyId;

	public function setGarplyId( int $garplyId ) : void {
		$this->garplyId = $garplyId;
	}

	public function getGarplyId() : int {
		return $this->garplyId;
	}

}
