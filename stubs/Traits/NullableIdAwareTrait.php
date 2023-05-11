<?php

namespace Stubs\Traits;

trait NullableIdAwareTrait {

	protected ?int $id = null;

	public function setId( ?int $id ) : void {
		$this->id = $id;
	}

	public function getId() : ?int {
		return $this->id;
	}

	public function mustGetId() : int {
		$id = $this->getId();
		if( !is_int($id) ) {
			throw new \RuntimeException('id error');
		}

		return $id;
	}

}
