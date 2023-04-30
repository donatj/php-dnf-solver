<?php

namespace Objects\Person;

use Traits\GarplyIdAwareTrait;
use Traits\NullableIdAwareTrait;
use Traits\QuxIdAwareTrait;

class Person implements PersonInterface {

	use GarplyIdAwareTrait;
	use QuxIdAwareTrait;
	use NullableIdAwareTrait;

	protected string $nameFirst;
	protected string $usernameDisplay;

	public function getUsername() : string {
		return $this->usernameDisplay;
	}

	public function setUsername( string $username ) : void {
		$this->usernameDisplay = $username;
	}

	public function setName( string $name ) : void {
		$this->nameFirst = $name;
	}

	public function getName() : string {
		return $this->nameFirst ?: '';
	}

	public function jsonSerialize() : array {
		return [
			"id"        => $this->getId(),
			"garply_id" => $this->getGarplyId(),
			"qix_id"    => $this->getQuxId(),
			"name"      => $this->getName(),
			"username"  => $this->getUsername(),
		];
	}

}
