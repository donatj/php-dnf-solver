<?php

namespace Stubs\Objects\Person;

use JsonSerializable;
use Stubs\Interfaces\GarplyMemberInterface;

interface PersonInterface extends GarplyMemberInterface, JsonSerializable {

	public function setName( string $name ) : void;

	public function getName() : string;

	public function setUsername( string $username ) : void;

	public function getUsername() : string;

	public function jsonSerialize() : array;

}
