<?php

namespace Stubs\Interfaces;

interface QuxIdAwareInterface {

	public function setQuxId( string $quxId ) : void;

	public function getQuxId() : string;

}
