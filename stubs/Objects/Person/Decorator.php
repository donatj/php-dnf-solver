<?php

namespace Stubs\Objects\Person;

abstract class Decorator implements PersonInterface {

	protected PersonInterface $user;

	public function __construct( PersonInterface $user ) {
		$this->user = $user;
	}

	public function setId( ?int $id ) : void {
		if( $this->user->getId() === null ) {
			$this->user->setId($id);
		}
	}

	public function getId() : ?int {
		return $this->user->getId();
	}

	public function mustGetId() : int {
		return $this->user->mustGetId();
	}

	public function setGarplyId( int $garplyId ) : void {
		$this->user->setGarplyId($garplyId);
	}

	public function getGarplyId() : int {
		return $this->user->getGarplyId();
	}

	public function setName( string $name ) : void {
		$this->user->setName($name);
	}

	public function getName() : string {
		return $this->user->getName();
	}

	public function setUsername( string $username ) : void {
		$this->user->setUsername($username);
	}

	public function getUsername() : string {
		return $this->user->getUsername();
	}

	public function setQuxId( string $quxId ) : void {
		$this->user->setQuxId($quxId);
	}

	public function getQuxId() : string {
		return $this->user->getQuxId();
	}

	public function jsonSerialize() : array {
		return $this->user->jsonSerialize();
	}

}
