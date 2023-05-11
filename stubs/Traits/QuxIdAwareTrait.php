<?php

namespace Stubs\Traits;

trait QuxIdAwareTrait {

	protected string $quxId;

	public function setQuxId( string $quxId ) : void {
		if( empty($this->quxId) ) {
			$this->quxId = $quxId;
		}
	}

	public function getQuxId() : string {
		return $this->quxId;
	}

}
