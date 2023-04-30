<?php

namespace Interfaces;

interface NullableIdAwareInterface {

	public function setId( ?int $id ) : void;

	public function getId() : ?int;

	public function mustGetId() : int;

}
